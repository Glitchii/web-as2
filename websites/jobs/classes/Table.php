<?php
/**
 * Used to access a table in a database. It should only be used by the Database class,
 * unless you create your own PDO instace and pass it along with a table name to the constructor.
 */

namespace Classes;

class Table {
    private $pdo;
    private $table;
    
    public function __construct($pdoInstance, $table) {
        $this->pdo = $pdoInstance;
        $this->table = $table;
    }

    /**
     * Wrapper function for the SELECT query.
     * 
     * 
     * @param $binds any An array of binds and strings to be used in the condition eg. ['id' => 1] or ['id' => 1, 'AND', 'archived' => 0]. $bind keys must match the column names in the table.
     * @param $items string|array Items to select. Can be an array of values eg. ['title', 'description'], a single string eg. 'title', or empty default for default '*'
     * @param $addition string The condition wg. WHERE, ORDER BY, etc. eg. 'WHERE id = :id'
     * 
     * Better explained with examples:
     *  
     * $db->job->select([], 'id') result in:  
     * SELECT id FROM job
     * 
     * $db->job->select(['id' => $jobId, 'AND', 'categoryId' => 1]) results in:  
     * SELECT * FROM job WHERE id = :id AND categoryId = :categoryId
     * Values are then bound to the query and executed.
     * 
     * $db->job->select(['id' => $jobId, 'ORDER BY id']) or:  
     * job->selector(['id' => $jobId, 'ORDER', 'BY id']) result in:  
     * SELECT * FROM job WHERE id = :id ORDER BY id
     * 
     * $db->job->select(['id' => $jobId, 'archived' => 1]) results in:
     * SELECT * FROM job WHERE id = :id, archived = 1
     * Which is invlid SQL because AND, OR, etc. are not used.
     * 
     * $db->job->select(['id' => $jobId, 'OR', true, 'AND', '1 = 1'], ['id', 'title']) results in:  
     * SELECT id, title FROM job WHERE id = :id OR 1 AND 1 = 1
     * true is automatically translated to 1 in the example above because true is just 1 and false is 0.
     */
    public function select(array|string $binds = [], array|string $items = '*') {
        return $this->selector($binds, $items)->fetch();
    }

    /** Same as select() but fetches all rows instead of just one. */
    public function selectAll(array|string $binds = [], array|string $items = '*'): array {
        return $this->selector($binds, $items)->fetchAll();
    }

    /** Inserts a row into the table. Example of $keysAndValues: ['title' => 'Job title'] */
    public function insert(array $keysAndValues = []) {
        $keys = array_keys($keysAndValues);
        $fields = implode(', ', $keys);
        $values = implode(', :', $keys);

        $statement = $this->pdo->prepare("INSERT INTO $this->table ($fields) VALUES (:$values)");
        $statement->execute($keysAndValues);
        return $this->pdo->lastInsertId();
    }

    /**
     * Method for updating a row in the table.
     * Unlike select() and selectAll(), $binds don't go first because 'WHERE'   
     * and other statements might not always be required but $values are always required.
     * 
     * Usage examples:   
     * $db->job->update(['archived' => true], ['id' => $jobId]); results in:   
     * UPDATE job SET archived = :archived WHERE id = :id; which is then executed as:   
     * UPDATE job SET archived = 1 WHERE id = 1;   
     */
    public function update(array $values, array $binds = []) {
        $fields = [];
        foreach ($values as $key => $value)
            $fields[] = "$key = :$key";

        $fields = implode(', ', $fields);

        $query = "UPDATE $this->table SET $fields";
        $statement = $this->bindAndExecute($binds, $query, $values);
        return $statement->rowCount();
    }

    /** Deletes a row from the table. $binds is the same as in select() and selectAll(). */
    public function delete(array $binds) {
        $query = "DELETE FROM $this->table";
        $statement = $this->bindAndExecute($binds, $query);
        return $statement->rowCount();
    }

    /**
     * Searches the table for a string in the specified fields. This method allows using the LIKE operator unlike select() and selectAll().
     * 
     * @param array $binds An array of fields and strings to search for eg. ['location' => %ampton%, 'title' => '%']
     * @param array $additionalBinds An array of additional binds to be used in the query eg. ['name' => 'John', 'ORDER BY id']
     * 
     * @return array An array of rows that match the search with additional binds applied if any.
     */
    public function search(array $binds, array $additionalBinds = []) {
        $query = "SELECT * FROM $this->table WHERE ";
        foreach ($binds as $key => $value)
            $query .= "$key LIKE :$key AND ";

        $query = substr($query, 0, -5); // Remove the last ' AND '
        // Combine the additional binds if any
        $query = $this->combine($additionalBinds, $query, 'AND');
        $statement = $this->bind($binds, $query, $additionalBinds);

        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * Function used by other fucntions to combine (not bind) keys and placeholder names in a string.
     * Example: ['location' => 'Northampton'] becomes 'location = :location' after being passed to this function.
     * It is then safe to use in a query and bind the values using the $this->bind() function or PDOStatement::execute() or PDOStatement::bindParam().
     */
    private function combine($binds, $query, $keyword, $debugMode = false): string {
        if (is_string($binds))
            // $binds is a string eg. 'ORDER BY id', we just need to apped it to the query
            $query .= " $binds ";
        else if (!empty($binds)) {
            // $binds is an array of binds or strings eg. ['id' => 1, 'AND', 'archived' => 0]
            // In this case we need to use the WHERE keyword and bind the values
            $query .= " $keyword ";
            foreach ($binds as $key => $value)
                // If $key is an index then it means that the value is a condition (eg. AND) without a binding
                $query .= is_int($key) ? " $value " : " $key = :$key ";
        }

        // Use debugMode to see the final query
        $debugMode && var_dump($query);

        return $query;
    }

    /**
     * @param array|string $binds A string or array of SQL conditions and binds eg. ['id' => 1, 'AND', 'archived' => 0].
     * @param string $query An incomplete query eg. 'SELECT * FROM job'. The WHERE and others statements are added in and completed from this function.
     * @param string $statement A statment which has possibly previously been prepared.
     */
    private function bindAndExecute($binds, $query, $values = [], $keyword = 'WHERE', $debugMode = false) {
        // Bind the WHERE and others statements
        $query = $this->combine($binds, $query, $keyword, $debugMode);

        // Prepare query and bind all keys that are not integers to their values
        $statement = $this->pdo->prepare($query);

        // Required by update() not select() and selectAll()
        foreach ($values as $key => $value)
            $statement->bindValue($key, $value);

        if (is_array($binds))
            foreach ($binds as $key => $value)
                if (!is_int($key))
                    $statement->bindValue($key, $value);
        
        // If $debugMode is enabled, output query string with binds appended + $binds array.
        $debugMode && var_dump($statement->queryString);
        $debugMode && var_dump($binds);
        
        // Execute the query and return the statement
        $statement->execute();
        return $statement;
    }

    /** Private function used by select() and selectAll() refer to them for documentation */
    private function selector($binds, $items) {
        $query = "SELECT ";

        if (is_array($items))
            $items = implode(', ', $items);
        
        $query .= "$items FROM $this->table";
        $statement = $this->bindAndExecute($binds, $query);
        return $statement;
    }

    private function bind($binds, $query, $additionalBinds = []){
        // Prepare query and bind $binds
        $statement = $this->pdo->prepare($query);
        foreach ($binds as $key => $value)
            $statement->bindValue($key, $value);

        // Also bind the additional binds if any, ignoring keys that are integers
        foreach ($additionalBinds as $key => $value)
            if (!is_int($key))
                $statement->bindValue($key, $value);

        return $statement;
    }
}

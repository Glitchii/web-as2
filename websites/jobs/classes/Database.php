<?php
/**
 * Connects to a database and allows accessing tables as properties. eg. `(new Database())->jobs->select('*');`   
 * where 'jobs' is the name of a table in the database. Methods such as select, insert, update, etc. are available on the table object.
 */

namespace Classes;

use \PDO;
use \stdClass;
use \PDOException;

class Database extends stdClass {
    // In PHP 8.2+, dynamic properties are deprecated and removed in PHP 9 according to https://wiki.php.net/rfc/deprecate_dynamic_properties.
    // stdClass is an empty class that allows dynamically adding properties. Inheriting it is a work around (https://php.watch/versions/8.2/dynamic-properties-deprecated)
    private $pdo;
    
    public function __construct($user, $password, $dbname, $host = "mysql") {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        } catch (PDOException $e) {
            $this->pdo = new PDO("mysql:host=127.0.0.1;dbname=$dbname", $user, $password);
        }

        // Using PDO::FETCH_OBJ instead of PDO::FETCH_ASSOC allows accessing columns as properties instead of array keys.
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    /**
     * Instead of manually creating properties for all the tables in the database,   
     * I use the __get magic method to create a new Table object when a property is accessed that doesn't exist.   
     */
    public function __get($name) {
        if (property_exists($this, $name) && isset($this->$name))
            return $this->$name;

        // I save the new Table object to the Database object so that it doesn't have to be created again.
        $this->$name = new Table($this->pdo, $name);
        return $this->$name;
    }
}
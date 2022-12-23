<?php
/**
 * The page class is used to create a page object. It is used to create the head and footer of a page etc.
 * Also has methods to, check if param is set on page URL, redirect to different pages, check if user is logged into the page, etc.
 */

class Page {
    public $db; // Database class instance
    public $categories; // Array of categories from the database

    public function __construct(Database $db = null) {
        $this->db = $db ?? new Database();
    }

    /** @return bool True if the user is logged in, false otherwise. */
    public function loggedIn(): bool {
        return isset($_SESSION['loggedIn']);
    }

    /** @return bool True if the user is staff, false otherwise. */
    public function isStaff(): bool {
        return $this->loggedIn() && $this->db->account->select(['id' => $_SESSION['loggedIn'], 'and', 'isAdmin' => true]);
    }
    
    /** Redirects to a URL and exits with an optional message. */
    public function redirect(string $url, string $message = null) {
        if (headers_sent())
            exit("<p>" . ($message ?? "Failed redirecting to $url because headers have already been sent.") . "</p><p><a href='$url'> Click here to continue</a></p>");

        header("Location: $url");
        exit($message ?? "Redirecting... to $url");
    }

    /** Gets a param/field from $_GET or $_POST, if not found, exits with an error message. */
    public function requiredParam(string $name): string {
        $param = $_GET[$name] ?? $_POST[$name] ?? null;

        if ($param === null)
            // If a page that requires a param eg. 'id' is accessed without it, it probably means the user manually typed the URL.
            exit("Parameter '$name' not specified, you probably weren't meant to be here.");
        return $param;
    }

    /** Called on pages that requires a user to be logged in as a staff member. */
    public function staffOnly(): array {
        if (!isset($_SESSION['loggedIn']))
            exit("<p>You are not logged in. <a href='/admin/index.php'>Login</a></p>");

        // Check thast the user is part of staff using id from session
        $user = $this->db->account->select(['id' => $_SESSION['loggedIn']]);
        if (!$user || $user['isAdmin'] == 0)
            exit("<p>You must be a staff member to access this page.</p>");

        return $user;
    }

    /**
     * Validates a job form and exits with an error message if invalid.
     * Web requests can be altered outside a browser form, so we still need to validate the data server side.
     * @return array An array of fields with values from the form if valid.
     */
    public function validateJobForm(): array {
        $fields = [
            'title' => $_POST['title'] ?? null,
            'description' => $_POST['description'] ?? null,
            'salary' => $_POST['salary'] ?? null,
            'location' => $_POST['location'] ?? null,
            'categoryId' => $_POST['categoryId'] ?? null,
            'closingDate' => $_POST['closingDate'] ?? null,
            // If user is accessing this page, they must be logged in.
            'accountId' => $_SESSION['loggedIn'] ?? null,
        ];

        // 
        // Verify that all fields are not empty
        foreach ($fields as $field)
            !$field && exit("All fields are required.");

        // Salary does not need to be a number, it can have a range eg. 20,000 - 30,000, currency symbols,
        // And other stuff the user might want to add, eg. a comment or information about the salary eg. "negotiable".
        // Featre to sort jobs by salary is not asked by the client, so we don't need a number anyway.

        // Verify that the closing date is in the future
        if (strtotime($_POST['closingDate']) < time())
            exit("Closing date must be in the future.");

        // Verify that category exists the in database
        if (!$this->db->category->select(['id' => $_POST['categoryId']]))
            exit("Category does not exist.");

        // Return the fields array if everything is valid.
        return $fields;
    }

    /** Creates doctype, html, head and title tags, etc. */
    public function createHead($title = "Home") {
        // Every page that needs a head and header also needs the categories for the navigation menu.
        // I assign the categories here so every pages can always have updated categories.
        $this->categories = $this->db->category->selectAll();

        // require 'include/top-section.html.php' to render the head with '$title' as well as the header and navigation menu.
        // Since the admin pages are in a subfolder, we need to go up a level to get to the include folder.
        require (strpos($_SERVER['REQUEST_URI'], '/admin') === 0 ? '../../' : '../') . 'include/top-section.html.php';
    }

    /** Checks whether current user owns a job or is staff. */
    public function isOwnerOrAdmin(int $jobId): bool {
        if (!$this->loggedIn()) return false;

        // Confirm that the jobs exists
        $job = $this->db->job->select(['id' => $jobId]);
        if (!$job) return false;

        // Check that the current user is the creator of the job or is staff
        if ($job['accountId'] != $_SESSION['loggedIn'] && !$this->isStaff())
            return false;

        return true;
    }
}

<?php

/**
 * Page class contains methods related to pages eg. methods to create the head and footer of a page or a
 * methods to check if a param is set on a page's URL, or to redirect to different page, or to check if a user is logged in,
 * etc.
 */

class Page {
    public $db; // Database class instance
    public $categories; // Array of categories from the database

    public function __construct(Database $db = null) {
        $this->db = $db ?? new Database();
    }

    /** @return bool Checks whether the user is logged into an account on the page. */
    public function loggedIn(): bool {
        return isset($_SESSION['loggedIn']);
    }

    /** @return bool Checks whether the user is logged in as staff. */
    public function isStaff(): bool {
        return $this->loggedIn() && $this->db->account->select(['id' => $_SESSION['loggedIn'], 'and', 'isAdmin' => true]);
    }
    
    /** Redirects to a URL and exits with an optional message. */
    public function redirect(string $url, string $message = null) {
        if (headers_sent()) {
            $script = "<script>location.href='$url';</script>"; // User will be told to click the link if JavaScript is disabled.
            exit("$script <p>" . ($message ?? "Failed redirecting to $url because headers have already been sent.") . "</p><p><a href='$url'> Click here to continue</a></p>");
        }

        header("Location: $url");
        exit($message ?? "Redirecting... to $url");
    }

    /** Gets a param/field from $_GET or $_POST, if not found, exits with an error message. */
    public function param(string $name, $required = true): string|null {
        $param = $_GET[$name] ?? $_POST[$name] ?? null;

        if ($param === null && $required)
            // If a page that requires a param eg. 'id' is accessed without it, it probably means the user manually typed the URL.
            exit("Parameter '$name' not specified, you probably weren't meant to be here.");
        
        return $param;
    }

    /** Called on pages that require a user to be logged in as a staff member. */
    public function staffOnly(): array {
        if (!isset($_SESSION['loggedIn']))
            $this->redirect('/admin', "You must log in to access this page.");

        // Check thast the user is part of staff using id from session
        $user = $this->db->account->select(['id' => $_SESSION['loggedIn']]);
        if (!$user || $user['isAdmin'] == 0)
            exit("<p>You must be a staff member to access this page.</p>");

        return $user;
    }

    /** Creates doctype, html, head and title tags, etc. */
    public function createHead($title = "Home") {
        // Every page that needs a head and header also needs the categories for the navigation menu.
        // I assign the categories here so every pages can always have updated categories.
        $this->categories = $this->db->category->selectAll();

        // Render the head with '$title' as well as the header and navigation menu.
        require "../templates/topsection.html.php";
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


    public function renderTemplate(string $template, array $data = []) {
        // Template will have access to variables in the scope eg. $this, $db, $data, etc.
        // It will not have access to variables in the scope of the function calling this function.
        // So, we use $data to pass the required variables into the scope which the template will have access to.
        extract($data);
        require "../templates/$template.html.php";
    }

    public function renderPage(string $page, string $title = null, array $data = []) {
        // 'admin/login' becomes 'Admin Login'
        $this->createHead(ucwords(str_replace('/', ' ', $title ?? $page)));
        
        extract($data);
        require "../pages/$page.php";
    }
}

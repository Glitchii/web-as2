<?php

/**
 * To avoid duplication,
 * this files contains variables and functions used in most files.
 */

session_start();

/** Gets a param/field from $_GET or $_POST, if not found, exits with an error message. */
function requiredParam(string $name): string {
    $param = $_GET[$name] ?? $_POST[$name] ?? null;

    if ($param === null)
        // If a page that requires a param eg. 'id' is accessed without it, it probably means the user manually typed the URL.
        exit("Parameter '$name' not specified, you probably weren't meant to be here.");
    return $param;
}

/** Redirects to a URL and exits with an optional message. */
function redirect(string $url, string $message = null) {
    if (headers_sent())
        exit("<p>" . ($message ?? "Failed redirecting to $url because headers have already been sent.") . "</p><p><a href='$url'> Click here to continue</a></p>");
        
    header("Location: $url");
    exit($message ?? "Redirecting... to $url");
}

function staffPage(): array {
    if (!isset($_SESSION['loggedIn']))
        exit("<p>You are not logged in. <a href='/admin/index.php'>Login</a></p>");

    global $db; // Avoid having to pass the $db argument everytime.
    // Check thast the user is part of staff using id from session
    $user = $db->account->select(['id' => $_SESSION['loggedIn']]);
    if (!$user || $user['isAdmin'] == 0)
        exit("<p>You must be a staff/admin to access this page.</p>");

    return $user;
}

/**
 * Validates a job form and exits with an error message if invalid.
 * Web requests can be altered outside a browser form, so we still need to validate the data server side.
 * @return array An array of fields with values from the form if valid.
 */
function validateJobForm(Database $db): array {
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
    if (!$db->category->select(['id' => $_POST['categoryId']]))
        exit("Category does not exist.");

    // Return the fields array if everything is valid.
    return $fields;
}

/** Creates doctype, html, head and title tags, etc. */
function createHead($title = "Home") {
    // Using 'global' so we don't have to make a new database instance in every file that uses this function.
    // Every file that requires a head will also need a database connection and categories list for navigation menu,
    // so it's better to define them here.
    global $categories, $db;

    $db ??= new Database();
    $categories = $db->category->selectAll();

    // Render the head with '$title' as well as the header and navigation menu.
    require 'top-section.html.php';
}

/** Checks whether current user owns a job or is staff. */
function isOwnerOrAdmin(Database $db, int $jobId): bool {
    if (!loggedIn()) return false;
    
    // Confirm that the jobs exists
    $job = $db->job->select(['id' => $jobId]);
    if (!$job) return false;

    // Check that the current user is the creator of the job or is staff
    if ($job['accountId'] != $_SESSION['loggedIn'] && !isStaff())
        return false;
    
    return true;
}

/** Checks whether current user is staff. */
function isStaff(): bool {
    global $db; // Avoid having to pass the $db argument everytime.
    return loggedIn() && $db->account->select(['id' => $_SESSION['loggedIn'], 'and', 'isAdmin' => true]);
}

/** Checks whether current user is logged in. */
function loggedIn(): bool {
    return isset($_SESSION['loggedIn']);
}

// Automatically require classes when they are used.
spl_autoload_register(fn ($class) => require_once __DIR__ . "/../classes/" . strtolower($class) . ".php");

<?php
/**
 * To avoid duplication,
 * this files contains variables and functions used in most files.
 */

session_start();
$loggedIn = ($_SESSION['loggedin'] ?? false) !== false;

/** Gets a param/field from $_GET or $_POST, if not found, exits with an error message. */
function requiredParam(string $name): string {
    $param = $_GET[$name] ?? $_POST[$name] ?? null;

    if ($param === null)
        // If a page that requires a param eg. 'id' is accessed without it, it probably means the user manually typed the URL.
        exit("Parameter '$name' not specified, you probably weren't meant to be here.");
    return $param;
}

/** Handles login form submission */
function handleLogin() {
    // Login form might be submitted on any page and (better to be) handled from the same page.
    // Using 'global' for $db so we don't have to keep passing the instance to the function, as it should already be defined.
    global $db, $loggedIn;

    if (isset($_POST['password']))
        // ?? and ?: instead of just ?? because $_POST['username'] might be set but empty.
        if ($user = $db->user->select(['username' => $_POST['username'] ?? '' ?: 'admin']))
            if (password_verify($_POST['password'], $user['password']))
                $loggedIn = $_SESSION['loggedin'] = true;
}

/** Redirects to a URL and exits with an optional message. */
function redirect(string $url, string $message = null) {
    header("Location: $url");
    $message && exit($message ?? "Redirecting... to $url");
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
    // Using 'global' so we don't have to make a new PDO instance in every file that uses this function.
    // Every file that requires a head will also need a database connection and categories list for navigation menu,
    // so it's better to create define them here.
    global $categories, $db;
    
    $db ??= new Database();
    $categories = $db->category->selectAll();

    // Render the head with '$title' as well as the header and navigation menu.
    require 'top-section.html.php';
}

// Automatically require classes when they are used.
spl_autoload_register(fn($class) => require_once __DIR__ . "/../classes/" . strtolower($class) . ".php");

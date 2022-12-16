<?php
/*
 * To avoid duplication,
 * this files contains variables and functions used in most files.
 */

session_start();
$loggedIn = ($_SESSION['loggedin'] ?? false) !== false;

// Function to create a database connection and return the PDO instance
function dbConnection($username = "student", $password = "student", $dbname = "job", $host = "mysql") {
    return new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
}

// Gets a param/field from $_GET or $_POST, if not found, exits with an error message.
function requiredParam($name) {
    $param = $_GET[$name] ?? $_POST[$name] ?? null;

    if ($param === null)
        // If a page that requires a param eg. 'id' is accessed without it, it probably means the user manually typed the URL.
        exit("Parameter '$name' not specified, you probably weren't meant to be here.");
    return $param;
}

// Handles login form submission
function handleLogin() {
    // Login form might be submitted on any page and (better to be) handled from the same page.
    // Using 'global' for $pdo so we don't have to keep passing the PDO instance to the function, as it should already be defined.
    global $pdo, $loggedIn;

    if (isset($_POST['password'])) {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE username = :username AND password = :password");
        $stmt->execute(['password' => $_POST['password'], 'username' => $_POST['username'] ?? '' ?: 'admin']);
    
        if ($stmt->fetch())
            $loggedIn = $_SESSION['loggedin'] = true;
    }
}

// Creates doctype, html, head and title tags, etc.
function createHead($title = "Home") {
    // Using 'global' so we don't have to make a new PDO instance in every file that uses this function.
    // Every file that requires a head will also need a database connection and categories list for navigation menu,
    // so it's better to create define them here.
    global $pdo, $categories;
    
    $pdo = dbConnection();
    $stmt = $pdo->prepare('SELECT * FROM category');
    $stmt->execute();
    $categories = $stmt->fetchAll();

    // Render the head with '$title' as well as the header and navigation menu.
    require 'top-section.html.php';
}

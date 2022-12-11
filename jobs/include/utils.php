<?php
/*
 *  This files contains variables and functions that are used in multiple files.
 *  This is used to avoid code duplication.
 */

session_start();
$loggedIn = ($_SESSION['loggedin'] ?? false) !== false;

// Function to create a database connection and return the PDO instance
function dbConnection($username = "asdf", $password = "asd", $dbname = "job", $host = "localhost", $charset = "utf8") {
    return new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $username, $password);
}

// Creates doctype, html, head and title tags, etc.
function createHead($title = "Home", $ConnectPDO = true) {
    // Tags will be closed from the script calling the function or automatically by the browser.
    // Using a function instead of a snippet allows for more flexibility. 
    echo <<< HTML
        <!DOCTYPE html>
        <html>

        <head>
            <title>Jo's Jobs - $title</title>
            <link rel="stylesheet" href="/styles.css" />
        </head>
    HTML;

    if (!$ConnectPDO) return;
    // If a database connection is needed, create it, I create a global variable to store the PDO instance.
    // I put the connection part in createHead() so files that need both don't have to call both functions.
    // Head will be required in all files anyway.
    global $pdo;
    $pdo = dbConnection();
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

function createHeader() {
    // if <body> is not added before this function is called,
    // the browser will automatically add it and can be closed automatically.
    echo <<< HTML
        <header>
            <section>
                <aside>
                    <h3>Office Hours:</h3>
                    <p>Mon-Fri: 09:00-17:30</p>
                    <p>Sat: 09:00-17:00</p>
                    <p>Sun: Closed</p>
                </aside>
                <h1>Jo's Jobs</h1>
            </section>
        </header>
    HTML;
}

function createNav ($banner=true) {
    echo <<< HTML
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                <li>Jobs
                    <ul>
                        <li><a href="/it.php">IT</a></li>
                        <li><a href="/hr.php">Human Resources</a></li>
                        <li><a href="/sales.php">Sales</a></li>

                    </ul>
                </li>
                <li><a href="/about.html">About Us</a></li>
                <li><a href="/faqs.php">FAQs</a></li>
            </ul>
        </nav>
    HTML;

    if ($banner)
        echo '<img src="/images/randombanner.php" />';
}

// Redirects to specified page
function redirect($path = '/') {
    // header("Location: $path"); // Headers might already be sent, so we'll use javascript instead
    exit("<script>location.href = '$path'</script>");
}
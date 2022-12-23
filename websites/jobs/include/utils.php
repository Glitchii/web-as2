<?php

/**
 * This file contains language constructs or elements used in most files.
 * It should be included in most if not all files.
 */

// Start the session to allow for session variables.
session_start();

// Automatically require classes when they are used.
spl_autoload_register(fn ($class) => require_once __DIR__ . "/../classes/" . strtolower($class) . ".php");

// Initialise a database connection.
$db = new Database($user = "student", $password = "student", $dbname = "job");

// Page class contains methods for common page tasks.
$page = new Page($db);

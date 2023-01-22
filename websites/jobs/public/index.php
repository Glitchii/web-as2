<?php
tempAdedJustSoICanFoldTheCode: {
	// Start the session to allow for session variables.
	session_start();

	// Automatically require classes when they are used.
	spl_autoload_register(function ($name) {
		$class = str_replace('\\', '/', '../classes/' . $name) . '.php';
		$controller = '../' . lcfirst(str_replace('\\', '/', $name)) . '.php';
		$interface = str_replace('\\', '/', '../interfaces/' . $name) . '.php';

		if (file_exists($class))
			require_once $class;
		else if (file_exists($controller))
			require_once $controller;
		else if (file_exists($interface))
			require_once $interface;
	});

	// Initialise a database connection.
	$db = new Database($user = 'student', $password = 'student', $dbname = 'job');

	// Page class contains methods for common page tasks.
	$page = new Page($db);

	/**
	 * Truncate a string if it exceeds a certain length
	 *
	 * @param string $str   The string to be truncated
	 * @param int    $len   The maximum length of the string, defaults to 20
	 * @return string       The truncated string with "..." appended if necessary
	 */
	function sub($str, $len = 20) {
		return strlen($str) >= $len ? substr($str, 0, $len) . '...' : $str;
	}
}

$uriSegments = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);;
$location = $uriSegments[1] ?? ''; // If URL is "/admin/jobs", $uriSegments[1] is "admin"
$controllerPath = '../controllers/' . ucfirst($location) . '.php';
$pagePath = '../pages/$location.php';

// Check if controller exists
if (file_exists($controllerPath)) {
	// Create controller and let it handle the page
	$controller = '\\Controllers\\' . ucfirst($location);
	$controller = new $controller($db, $uriSegments);
}

// // Controller doesn't exist but a page might
// else if (file_exists($pagePath)) {
// 	require_once $pagePath;
// }

// Nothing is found, fall back to home page
else {
	$controller = new \Controllers\Home($db);
}

include '../templates/footer.html.php';

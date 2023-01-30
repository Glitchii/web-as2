<?php

session_start();

// Register a function to handle autoloading of classes
spl_autoload_register(function ($className) {
	$classPath = str_replace('\\', '/', '../' . lcfirst($className)) . '.php';

	if (file_exists($classPath))
		require_once $classPath;
});

$entryPoint = new Classes\EntryPoint();
$entryPoint->run();

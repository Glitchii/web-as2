<?php
require "../../include/utils.php";

// If user somehow arrives to this page when not logged in, redirect to categories.php, it has login form
!$loggedIn && exit(header('Location: categories.php'));
$categoryId = requiredParam('id');

dbConnection()
	->prepare('DELETE FROM category WHERE id = :id')
	->execute(['id' => $categoryId]);


header('location: categories.php');
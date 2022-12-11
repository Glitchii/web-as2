<?php
require "../../include/utils.php";

// If user somehow arrives to this page when not logged in, redirect to categories.php, it has login form
!$loggedIn && header('Location: categories.php');

dbConnection()
	->prepare('DELETE FROM category WHERE id = :id')
	->execute(['id' => $_POST['id']]);


header('location: categories.php');
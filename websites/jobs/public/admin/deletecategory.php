<?php
require "../../include/utils.php";

!$loggedIn && exit(header('Location: categories.php'));
$categoryId = requiredParam('id');

dbConnection()
	->prepare('DELETE FROM category WHERE id = :id')
	->execute(['id' => $categoryId]);


header('location: categories.php');
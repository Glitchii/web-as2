<?php
require "../../include/utils.php";

// If user somehow arrives to this page while not logged in, redirect to categories.php as it has a login form.
!$loggedIn && redirect('categories.php');
$categoryId = requiredParam('id');
$db ??= new Database();

$db->category->delete(['id' => $categoryId]);

redirect('categories.php');
<?php
require_once "../../include/utils.php";

$categoryId = requiredParam('id');
$db ??= new Database();

// This page can only be accessed by staff members.
staffPage();

// No need to also delete all jobs associated with the category. A database trigger will handle that.
$db->category->delete(['id' => $categoryId]);

redirect('categories.php');
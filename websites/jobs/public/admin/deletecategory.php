<?php
require "../../include/utils.php";

$categoryId = requiredParam('id');
$db ??= new Database();

// This page can only be accessed by admin.
adminPage($db);

// Delete category and all jobs associated with it.
$db->job->delete(['categoryId' => $categoryId]);
$db->category->delete(['id' => $categoryId]);

redirect('categories.php');
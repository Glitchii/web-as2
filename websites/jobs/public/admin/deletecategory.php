<?php
require_once "../../include/utils.php";

$categoryId = $page->requiredParam('id');
$page->staffOnly();

// No need to delete all jobs associated with the category, a trigger will do that for us.
$db->category->delete(['id' => $categoryId]);

$page->redirect('categories.php');
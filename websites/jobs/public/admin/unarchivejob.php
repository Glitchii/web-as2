<?php
require_once "../../include/utils.php";

!$page->loggedIn() && $page->redirect('index.php');
$jobId = $page->requiredParam('id');

$page->isOwnerOrAdmin($jobId) || $page->redirect('jobs.php');
$db->job->update(['archived' => 0], ['id' => $jobId]);

$page->redirect('jobs.php');

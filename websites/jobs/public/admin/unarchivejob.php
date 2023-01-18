<?php
require_once "../../utils/utils.php";

!$page->loggedIn() && $page->redirect('index.php');
$jobId = $page->param('id');

$page->isOwnerOrAdmin($jobId) || $page->redirect('jobs.php');
$db->job->update(['archived' => 0], ['id' => $jobId]);

$page->redirect('jobs.php');

<?php
require_once "../../include/utils.php";

!loggedIn() && redirect('index.php');
$jobId = requiredParam('id');
$db ??= new Database();

isOwnerOrAdmin($db, $jobId) || redirect('jobs.php');

$db->job->update(['archived' => 0], ['id' => $jobId]);

redirect('jobs.php');

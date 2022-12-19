<?php
require "../../include/utils.php";

// jobs.php has a login form.
!$loggedIn && redirect('jobs.php');
$jobId = requiredParam('id');
$db ??= new Database();

$db->job->delete(['id' => $jobId]);

redirect('jobs.php');
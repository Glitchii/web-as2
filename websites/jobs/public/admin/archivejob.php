<?php
require "../../include/utils.php";

// If user somehow arrives to this page when not logged in, redirect to jobs.php, it has login form
!$loggedIn && redirect('jobs.php');
$jobId = requiredParam('id');

(new Database())->job->update(['archived' => 1], ['id' => $jobId]);

redirect('jobs.php');

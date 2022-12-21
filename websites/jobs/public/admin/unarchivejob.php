<?php
require "../../include/utils.php";

// If user somehow arrives to this page when not logged in, redirect to jobs.php, it has login form
!$loggedIn && redirect('/admin/index.php');
$jobId = requiredParam('id');

(new Database())->job->update(['archived' => 0], ['id' => $jobId]);

redirect('jobs.php');

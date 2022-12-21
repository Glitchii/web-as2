<?php
require "../../include/utils.php";
// Client page

!$loggedIn && redirect('/admin/index.php');
$jobId = requiredParam('id');
$db ??= new Database();
$db->job->delete(['id' => $jobId]);

redirect('jobs.php');
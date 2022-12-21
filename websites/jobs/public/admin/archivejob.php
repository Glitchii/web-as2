<?php
require "../../include/utils.php";

!$loggedIn && redirect('/admin/index.php');
$jobId = requiredParam('id');

(new Database())->job->update(['archived' => 1], ['id' => $jobId]);

redirect('jobs.php');

<?php
require "../../include/utils.php";

// If user somehow arrives to this page when not logged in, redirect to jobs.php, it has login form
!$loggedIn && exit(header('Location: jobs.php'));
$jobId = requiredParam('id');

dbConnection()
	->prepare('DELETE FROM job WHERE id = :id')
	->execute(['id' => $jobId]);


header('location: jobs.php');
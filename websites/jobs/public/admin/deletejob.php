<?php
require_once "../../include/utils.php";

!$page->loggedIn() && $page->redirect('index.php');
$jobId = $page->requiredParam('id');

$page->isOwnerOrAdmin($jobId) || $page->redirect('jobs.php');

$rowCount = $db->job->delete(['id' => $jobId]);

// A database trigger will delete all applicants associated with the job.
// We just need to delete all CVs associated with the job. They start with 'job<id>-'.
try {
    // Confirm the job was deleted first.
    if ($rowCount > 0) {
        $files = glob("../cvs/job$jobId-*");
        foreach ($files as $file)
            unlink($file);
    }
} catch (Exception $e) {
    // Not a big deal I guess.
}

$page->redirect('jobs.php');

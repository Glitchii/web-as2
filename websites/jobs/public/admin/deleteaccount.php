<?php
require_once "../../include/utils.php";

$accountId = $page->requiredParam('id');
$user = $page->staffOnly();

// if the current user is the account. Log them out also.
if ($user['id'] == $accountId)
	session_destroy();

// Deleting all jobs associated with the account is handled by a database trigger. We just need to delete the account.
$db->account->delete(['id' => $accountId]);

$page->redirect('accounts.php');
?>
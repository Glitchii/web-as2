<?php
require_once "../../include/utils.php";

$accountId = requiredParam('id');
$db ??= new Database();

$currentUser = staffPage();

// if the current user is the account. Log them out before deleting the account.
if ($currentUser['id'] == $accountId)
	session_destroy();

// Deleting all jobs associated with the account is handled by a database trigger. So we just need to delete the account.
$db->account->delete(['id' => $accountId]);

redirect('accounts.php');
?>
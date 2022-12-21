<?php
require "../../include/utils.php";

$accountId = requiredParam('id');
$db ??= new Database();

$currentUser = adminPage($db);

// if the current user is the account. Log them out before deleting the account.
if ($currentUser['id'] == $accountId)
	session_destroy();

// Delete account plus all jobs associated with it.
$db->job->delete(['accountId' => $accountId]);
$db->account->delete(['id' => $accountId]);
redirect('accounts.php');
?>
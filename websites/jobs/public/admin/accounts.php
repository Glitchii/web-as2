<?php
require "../../include/utils.php";

createHead("Manage Account");

// Block access to this page if logged in user is not an admin.
$user = adminPage($db);
$accountType = $_POST['type'] ?? $_GET['type'] ?? null;

// Select up to 10 accounts, ordered by username in ascending order
if ($accountType) $accounts = $db->account->selectAll(['isAdmin' => $accountType === 'admin', 'order by username asc limit 10']);
else $accounts = $db->account->selectAll('order by username asc limit 10');
?>

<main class="sidebar">
	<section class="left">
		<h3>Type:</h3>
		<ul>
			<li><a href="?type=client" class="<?= $accountType === 'client' ? 'current' : '' ?>">Clients</a></li>
			<li><a href="?type=admin" class="<?= $accountType === 'admin' ? 'current' : '' ?>">Admins/Staff</a></li>
			<li><a href="<?= $_SERVER['PHP_SELF'] ?>" class="<?= !$accountType ? 'current' : '' ?>">All</a></li>
		</ul>
	</section>

	<section class="right">
		<h2>Accounts</h2>
		<div class="tablemenu">
			<a class="new" href="addaccount.php">Create</a>
		</div>

		<table>
			<thead>
				<tr>
					<th style="width: 15%">Username</th>
					<th style="width: 15%">Account type</th>
					<th style="width: 50%">&nbsp;</th>
					<th style="width: 15%">&nbsp;</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($accounts as $account) { ?>
					<tr class="<?= $account['id'] === $user['id'] ? 'currentUser' : '' ?>">
						<td><?= $account['username'] ?></td>
						<td><?= $account['isAdmin'] ? 'Admin' : 'Client' ?></td>
						<td>
							<form method="post" action="editaccount.php?id=<?= $account['id'] ?>">
								<input type="submit" value="Edit" class="link">
							</form>
						</td>
						<td>
							<form method="post" action="deleteaccount.php?id=<?= $account['id'] ?>">
								<input type="submit" value="Delete" class="link">
							</form>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</section>
</main>

<?php include '../../include/footer.html.php'; ?>

<script>
    // If a user being deleted is the current user, ask for confirmation.
	document.querySelector('tr.currentUser>td:last-child>form').addEventListener('submit', e => {
		if (!confirm("Are you sure you want to delete your own account? You will be logged out."))
			e.preventDefault();
	});
</script>
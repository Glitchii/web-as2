<main class="sidebar">
	<?php $this->leftSection($account ?? null, $accountType ?? ''); ?>

	<section class="right">
		<h2>Accounts</h2>
		<div class="tablemenu">
			<a class="new" href="/admin/accounts/modify">Create</a>
		</div>

		<table>
			<thead>
				<tr>
					<th style="width: 15%">Username</th>
					<th style="width: 15%">Account type</th>
					<th style="width: 50%">&nbsp;</th>
					<th style="width: 5%">&nbsp;</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($accounts as $account) { ?>
					<tr class="<?= $account['id'] === $user['id'] ? 'currentUser' : '' ?>">
						<td><?= $account['username'] ?></td>
						<td><?= $account['isAdmin'] ? 'Staff' : 'Client' ?></td>
						<td><a href="/admin/accounts/modify?id=<?= $account['id'] ?>" class="link" style="float: right;">Edit</a></td>
						<td><a href="/admin/accounts/?action=delete&id=<?= $account['id'] ?>" class="link delete" style="float: right;" data-confirm="Are you sure you want to delete <?= $account['id'] === $user['id'] ? 'your own account? You will be logged out.' : "the account '{$account['username']}'" ?>?">Delete</a></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</section>
</main>
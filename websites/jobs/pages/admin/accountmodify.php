<main class="sidebar">
	<?php $this->leftSection($account ?? null, $accountType ?? ''); ?>

	<section class="right">
		<h2><?= $pageType ?> Account</h2>

		<form method="post">
			<label for="username">Username</label>
			<input name="username" value="<?= $account->username ?? ''; ?>" required />
			<label for="password">Password</label>
			<input name="password" type="password" />
			<label for="type">Account type</label>
			<select name="type">
				<option value="staff" <?= $account->isAdmin ?? '' ? 'selected' : ''; ?>>Staff</option>
				<option value="client" <?= $account->isAdmin ?? '' ? '' : 'selected'; ?>>Client</option>
			</select>

			<input name="submit" type="submit" value="<?= $pageType ?>" />
		</form>
	</section>
</main>
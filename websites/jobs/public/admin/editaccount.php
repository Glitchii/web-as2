<?php
require_once "../../include/utils.php";

$accountId = requiredParam('id');
$db ??= new Database();

staffPage();
$account = $db->account->select(['id' => $accountId]);
!$account && redirect('accounts.php');

if (isset($_POST['submit'])) {
	$username = $_POST['username'] ?? null;
	$password = $_POST['password'] ?? null; // Might be defined but empty
	$type = $_POST['type'] ?? null;

	// All fields except password are filled with the current values by default. So they should not be empty.
	if (!$username || !$type)
		exit('Invalid form data');

	$account2 = $db->account->select(['username' => $username]);
	if ($account2 && $account2['id'] != $accountId)
		exit('Username already exists');

	$db->account->update([
		'username' => $username,
		'password' => $password ? password_hash($password, PASSWORD_DEFAULT) : $account['password'],
		'isAdmin' => $type === 'staff' ? 1 : 0
	], ['id' => $accountId]);

	redirect('accounts.php', 'Account updated.');
}

createHead("Add Account");
?>

<main class="sidebar">
	<section class="left">
		<h3>&nbsp;</h3>
		<ul>
			<li><a href="/admin/addaccount.php">Add</a></li>
			<li><a href class="current">Edit</a></li>
			<li><a href="/admin/accounts.php">Manage</a></li>
		</ul>
	</section>

	<section class="right">
		<h2>Edit Account</h2>

		<form method="POST">
			<label for="username">Username</label>
			<input name="username" value="<?= $account['username']; ?>" required />
			<label for="password">Password</label>
			<input name="password" type="password" />
			<label for="type">Account type</label>
			<select name="type">
				<option value="staff" <?= $account['isAdmin'] ? 'selected' : ''; ?>>Staff</option>
				<option value="client" <?= $account['isAdmin'] ? '' : 'selected'; ?>>Client</option>
			</select>

			<input name="submit" type="submit" value="Edit" />
		</form>
	</section>
</main>

<?php include '../../include/footer.html.php'; ?>
<?php
require_once "../../include/utils.php";

createHead("Add Account");
staffPage();
?>

<main class="sidebar">
	<section class="left">
		<h3>&nbsp;</h3>
		<ul>
			<li><a href class="current">Add</a></li>
			<li><a href="/admin/accounts.php">Manage</a></li>
		</ul>
	</section>

	<section class="right">
		<h2>
			<?php
			if (!isset($_POST['submit']))
				echo 'Add Account';
			else {
				$username = $_POST['username'];
				$password = $_POST['password'];
				$type = $_POST['type'];

				// Check if username is already taken
				if ($db->account->select(['username' => $username]))
					echo 'Username exists, try another.';
				else {
					$db->account->insert([
						'username' => $username,
						'password' => password_hash($password, PASSWORD_DEFAULT),
						'isAdmin' => $type === 'staff' ? 1 : 0
					]);

					echo 'Created, add another?';
				}
			} ?>
		</h2>

		<form method="POST">
			<label for="username">Username</label>
			<input name="username" required />
			<label for="password">Password</label>
			<input name="password" type="password" required />
			<label for="type">Account type</label>
			<select name="type">
				<option value="staff">Staff</option>
				<option value="client">Client</option>
			</select>

			<input name="submit" type="submit" value="Add" />
		</form>
	</section>
</main>

<?php include '../../include/footer.html.php'; ?>
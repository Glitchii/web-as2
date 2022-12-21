<?php
require "../../include/utils.php";

createHead("Admin Home");

// The use has submitted a login form.
if (isset($_POST['password']) && isset($_POST['username']))
	// ?? and ?: instead of just ?? because $_POST['username'] might be set but empty.
	if ($user = $db->account->select(['username' => $_POST['username']]))
		if (password_verify($_POST['password'], $user['password']))
			$loggedIn = $_SESSION['loggedin'] = $user['id'];
?>

<main class="sidebar">
	<?php if ($loggedIn) { ?>
		<section class="left">
			<ul>
				<li><a href="/admin/jobs.php">Jobs</a></li>
				<li><a href="/admin/categories.php">Categories</a></li>
			</ul>
		</section>

		<section class="right">
			<h2>You are now logged in</h2>
		</section>

	<?php } else { ?>
		<h2 style="margin-left: 50px;">Log in</h2>

		<form method="post" style="padding: 0 40px">
			<label for="username">Username</label>
			<input name="username" />
			<label for="password">Password</label>
			<input name="password" type="password" required />
			<input name="submit" type="submit" value="Log In" />
		</form>
	<?php } ?>
</main>

<?php include '../../include/footer.html.php'; ?>
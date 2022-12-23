<?php
require_once "../../include/utils.php";

createHead("Admin Home");

// The use has submitted a login form.
if (isset($_POST['password']) && isset($_POST['username']))
	// ?? and ?: instead of just ?? because $_POST['username'] might be set but empty.
	if ($user = $db->account->select(['username' => $_POST['username']]))
		if (password_verify($_POST['password'], $user['password']))
			$_SESSION['loggedIn'] = $user['id'];
?>

<main class="sidebar">
	<?php if (loggedIn()) { ?>
		<?php include '../../include/leftsection-staff.html.php'; ?>

		<section class="right">
			<?php if (isStaff()) { ?>
				<h2>Admin Area</h2>
				<p>Welcome to the Admin/Staff area. Here you can manage all jobs, categories, and accounts.</p>
			<?php } else { ?>
				<h2>Client Admin Area</h2>
				<p>Welcome to the client admin area. Here you can manage all jobs you created and see all applicants.</p>
			<?php } ?>
			<p>Start by selecting an option from the left.</p>
		</section>

	<?php } else { ?>
		<section class="left">
			<ul>
				<li><a href="/index.php">Back Home</a></li>
			</ul>
		</section>

		<section class="right">
			<h2 style="margin-left: 50px;">Log in</h2>

			<form method="post" style="padding: 0 40px">
				<label for="username">Username</label>
				<input name="username" />
				<label for="password">Password</label>
				<input name="password" type="password" required />
				<input name="submit" type="submit" value="Log In" />
			</form>
		</section>
	<?php } ?>
</main>

<?php include '../../include/footer.html.php'; ?>
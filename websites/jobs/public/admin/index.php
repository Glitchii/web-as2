<?php
require "../../include/utils.php";

createHead("Admin Home");
handleLogin();
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
		<h2>Log in</h2>

		<form method="post" style="padding: 40px">
			<label for="username">Username</label>
			<input name="username" type="password" />
			<label for="password">Password</label>
			<input name="password" type="password" required />
			<input name="submit" type="submit" value="Log In" />
		</form>
	<?php } ?>
</main>

<?php include '../../include/footer.html.php'; ?>
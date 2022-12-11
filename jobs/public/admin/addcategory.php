<?php
require "../../include/utils.php";

createHead("Add Category");
createHeader();
createNav();
handleLogin();

// If user somehow arrives to this page when not logged in, redirect to categories.php, it has login form
!$loggedIn && header('Location: categories.php');
?>

<main class="sidebar">

	<section class="left">
		<ul>
			<li><a href="jobs.php">Jobs</a></li>
			<li><a href="categories.php">Categories</a></li>
		</ul>
	</section>

	<section class="right">
		<?php if (isset($_POST['submit'])) {
			$pdo->prepare('INSERT INTO category (name) VALUES (:name)')->execute(['name' => $_POST['name']]);
			echo 'Category added';
		} else { ?>
			<h2>Add Category</h2>
			<form method="POST">
				<label>Name</label>
				<input type="text" name="name" />
				<input type="submit" name="submit" value="Add Category" />
			</form>
		<?php } ?>
	</section>
</main>

<?php include '../../include/footer.php'; ?>
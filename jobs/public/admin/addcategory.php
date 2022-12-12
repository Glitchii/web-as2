<?php
require "../../include/utils.php";

createHead("Add Category");
handleLogin();

// If user somehow arrives to this page when not logged in, redirect to categories.php, it has login form
!$loggedIn && exit(header('Location: categories.php'));
?>

<main class="sidebar">
	<?php include '../../include/leftside-admin.php'; ?>

	<section class="right">
		<?php if (isset($_POST['submit'])) {
			// Exit if the name field doesn't exist or is empty
			!($_POST['name'] ?? '') && exit("Category name is required.");

			// If a category with the same name already exists, provide a link to it
			$stmt = $pdo->prepare('SELECT id FROM category WHERE name = :name');
			$stmt->execute(['name' => $_POST['name']]);
			$categoryId = $stmt->fetch()['id'] ?? null;
			$categoryId && exit("Category with the same name already exists. <a href='/admin/categories.php?id=$id'>View it</a>.");

			// Finally, add the category
			$pdo->prepare('INSERT INTO category (name) VALUES (:name)')->execute(['name' => $_POST['name']]);
			echo 'Category added';
		} else { ?>
			<h2>Add Category</h2>
			<form method="POST">
				<label for="name">Name</label>
				<input name="name" type="text" required />
				<input name="submit" type="submit" value="Add Category" />
			</form>
		<?php } ?>
	</section>
</main>

<?php include '../../include/footer.php'; ?>
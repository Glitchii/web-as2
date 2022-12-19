<?php
require "../../include/utils.php";

// If user somehow arrives to this page when not logged in, redirect to categories.php, it has login form
!$loggedIn && redirect('categories.php');

createHead("Add Category");
handleLogin();
?>

<main class="sidebar">
	<?php include '../../include/leftside-admin.html.php'; ?>

	<section class="right">
		<?php if (isset($_POST['submit'])) {
			// Exit if the 'name' field doesn't exist or is empty
			empty($_POST['name']) && exit("Category name is required.");

			$categoryId = $db->category->select(['name' => $_POST['name']], 'id');
			if ($categoryId)
				echo "Category with the same name already exists. ",
					 "<a href='/admin/categories.php?id={$categoryId['id']}'>View it</a>.";
			else {
				$db->category->insert(['name' => $_POST['name']]);
				echo 'Category added';
			}
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

<?php include '../../include/footer.html.php'; ?>
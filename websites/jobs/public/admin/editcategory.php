<?php
require "../../include/utils.php";

!$loggedIn && redirect('categories.php');
$categoryId = requiredParam('id');
$db ??= new Database();

// Check if category exists and redirect to categories.php if it does not.
$category = $db->category->select(['id' => $categoryId]);
!$category && redirect('categories.php');

createHead("Edit Category");
handleLogin();
?>

<main class="sidebar">
	<?php include '../../include/leftside-admin.html.php'; ?>

	<section class="right">
		<?php if (isset($_POST['submit'])) {
			$db->category->update(['name' => $_POST['name']], ['id' => $categoryId]);
			echo 'Category Saved';
		} else { ?>
			<h2>Edit Category</h2>
			<form method="POST">
				<label for="name">Name</label>
				<input type="text" name="name" value="<?= $category['name']; ?>" />
				<input type="submit" name="submit" value="Save Category" />
			</form>
		<?php } ?>
	</section>
</main>

<?php include '../../include/footer.html.php'; ?>
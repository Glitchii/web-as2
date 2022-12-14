<?php
require_once "../../include/utils.php";

$categoryId = $page->requiredParam('id');

// Check if category exists and redirect to categories.php if it does not.
$category = $db->category->select(['id' => $categoryId]);
!$category && $page->redirect('categories.php');

$page->createHead("Edit Category");
$page->staffOnly();
?>

<main class="sidebar">
	<?php include '../../include/leftsection-staff.html.php'; ?>

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
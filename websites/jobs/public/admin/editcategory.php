<?php
require_once "../../utils/utils.php";

$categoryId = $page->param('id');

// Check if category exists and redirect to categories.php if it does not.
$category = $db->category->select(['id' => $categoryId]);
!$category && $page->redirect('categories.php');

$page->createHead("Edit Category");
$page->staffOnly();
?>

<main class="sidebar">
	<?php include '../../templates/leftsection-staff.html.php'; ?>

	<section class="right">
		<?php if (isset($_POST['submit'])) {
			$db->category->update(['name' => $_POST['name']], ['id' => $categoryId]);
			echo 'Category Saved';
		} else { ?>
			<h2>Edit Category</h2>
			<form method="post">
				<label for="name">Name</label>
				<input type="text" name="name" value="<?= $category['name']; ?>" />
				<input type="submit" name="submit" value="Save Category" />
			</form>
		<?php } ?>
	</section>
</main>

<?php include '../../templates/footer.html.php'; ?>
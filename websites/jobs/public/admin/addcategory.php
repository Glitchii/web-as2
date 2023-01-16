<?php
require_once "../../utils/utils.php";

$page->createHead("Add Category");
$page->staffOnly();
?>

<main class="sidebar">
	<?php include '../../templates/leftsection-staff.html.php'; ?>

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
			<form method="post">
				<label for="name">Name</label>
				<input name="name" type="text" required />
				<input name="submit" type="submit" value="Add Category" />
			</form>
		<?php } ?>
	</section>
</main>

<?php include '../../templates/footer.html.php'; ?>
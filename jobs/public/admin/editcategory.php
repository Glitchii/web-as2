<?php
require "../../include/utils.php";

// If user somehow arrives to this page when not logged in, redirect to categories.php, it has login form
!$loggedIn && exit(header('Location: categories.php'));
$categoryId = requiredParam('id');

createHead("Edit Category");
createHeader();
createNav();
handleLogin();
?>

<main class="sidebar">
	<?php include '../../include/admin-left.php'; ?>

	<section class="right">
		<?php if (isset($_POST['submit'])) {
			$pdo->prepare('UPDATE category SET name = :name WHERE id = :id ')->execute(['name' => $_POST['name'], 'id' => $_POST['id']]);
			echo 'Category Saved';
		} else {
			$stmt = $pdo->prepare('SELECT * FROM category WHERE id = :id');
			$stmt->execute(['id' => $categoryId]);
			$category = $stmt->fetch();

			// Category should exists, otherwise redirect to categories.php
			!$category && exit(header('Location: categories.php'));
		?>
			<h2>Edit Category</h2>
			<form method="POST">
				<label for="name">Name</label>
				<input type="text" name="name" value="<?= $category['name']; ?>" />
				<input type="submit" name="submit" value="Save Category" />
			</form>
		<?php } ?>
	</section>
</main>

<?php include '../../include/footer.php'; ?>
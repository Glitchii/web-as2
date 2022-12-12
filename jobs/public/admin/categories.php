<?php
require "../../include/utils.php";

// If id is passed, display the category page and exit. The rest is handled there.
isset($_GET['id']) && exit(require "../../include/category-page.php");

createHead("Categories");
handleLogin();
?>

<main class="sidebar">
	<?php include '../../include/leftside-admin.php'; ?>

	<section class="right">
		<?php if (!$loggedIn)
			// If user is not logged in, display log in form
			require '../../include/login-admin.php';
		else { ?>
			<h2>Categories</h2>
			<a class="new" href="addcategory.php">Add new category</a>

			<table>
				<thead>
					<tr>
						<th>Name</th>
						<th style="width: 5%">&nbsp;</th>
						<th style="width: 5%">&nbsp;</th>
					</tr>

					<?php
					// $categories is set in top-section.php
					if (!$categories)
						echo '<tr><td>No categories found</td></tr>';
					else
						foreach ($pdo->query('SELECT * FROM category') as $category) { ?>
							<tr>
								<td><?= $category['name'] ?></td>
								<td><a style="float: right" href="editcategory.php?id=<?= $category['id'] ?>">Edit</a></td>
								<td>
									<form method="post" action="deletecategory.php">
										<input type="hidden" name="id" value="<?= $category['id'] ?>" />
										<input type="submit" name="submit" value="Delete" />
									</form>
								</td>
							</tr>
						<?php } ?>
				</thead>
			</table>
		<?php } ?>
	</section>
</main>

<?php include '../../include/footer.php'; ?>
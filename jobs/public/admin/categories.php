<?php
require "../../include/utils.php";

createHead("Categories");
createHeader();
createNav();
handleLogin();
?>

<main class="sidebar">
	<section class="left">
		<ul>
			<li><a href="/admin/jobs.php">Jobs</a></li>
			<li><a href="/admin/categories.php">Categories</a></li>
		</ul>
	</section>

	<section class="right">
		<?php
		if (!$loggedIn)
			// If user is not logged in, display log in form
			require '../../include/login.php';
		else {
		?>
			<h2>Categories</h2>
			<a class="new" href="addcategory.php">Add new category</a>

			<table>
				<thead>
					<tr>
						<th>Name</th>
						<th style="width: 5%">&nbsp;</th>
						<th style="width: 5%">&nbsp;</th>
					</tr>

					<?php foreach ($pdo->query('SELECT * FROM category') as $category) { ?>
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
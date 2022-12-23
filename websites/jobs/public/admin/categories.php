<?php
require_once "../../include/utils.php";

createHead("Categories");
staffPage();
?>

<main class="sidebar">
	<?php include '../../include/leftsection-staff.html.php'; ?>

	<section class="right">
		<h2>Categories</h2>
		<a class="new" href="addcategory.php">Add new category</a>

		<table>
			<thead>
				<tr>
					<th>Name</th>
					<th style="width: 15%">&nbsp;</th>
					<th style="width: 15%">&nbsp;</th>
				</tr>

				<?php
				// $categories is set in top-section.html.php
				if (!$categories)
					echo '<tr><td>No categories found</td></tr>';
				else
					foreach ($categories as $category) { ?>
					<tr>
						<td><?= $category['name'] ?></td>
						<td><a style="float: right" href="editcategory.php?id=<?= $category['id'] ?>">Edit</a></td>
						<td>
							<form method="post" action="deletecategory.php">
								<input type="hidden" name="id" value="<?= $category['id'] ?>" />
								<input type="submit" name="submit" value="Delete" class="link" />
							</form>
						</td>
					</tr>
				<?php } ?>
			</thead>
		</table>
	</section>
</main>

<?php include '../../include/footer.html.php'; ?>

<script>
	// Warning that deleting a category will also delete all auctions in that category
	for (const anchor of document.querySelectorAll('td:last-child>form'))
		anchor.addEventListener('submit', e => {
			if (!confirm("Are you sure you want to delete this category?\n\nAll jobs in this category will also be deleted!"))
				e.preventDefault();
		});
</script>
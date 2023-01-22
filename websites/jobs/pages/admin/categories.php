<main class="sidebar">
	<?php $this->leftSection($categories); ?>

	<section class="right">
		<h2>Categories</h2>
		<a class="new" href="/admin/categories/modify">Add category</a>

		<table>
			<thead>
				<tr>
					<th>Name</th>
					<th style="width: 5%">&nbsp;</th>
					<th style="width: 5%">&nbsp;</th>
				</tr>

				<?php
				if (!($categories))
					echo '<tr><td>No categories found</td></tr>';
				else
					foreach ($categories as $category) { ?>
					<tr>
						<td><?= $category['name'] ?></td>
						<td><a style="float: right" href="/admin/categories/modify?id=<?= $category['id'] ?>">Edit</a></td>
						<td><a style="float: right" href="/admin/categories?action=delete&id=<?= $category['id'] ?>" class="link delete" data-confirm="Are you sure you want to delete this category? All jobs in this category will also be deleted!">Delete</a></td>
					</tr>
				<?php } ?>
			</thead>
		</table>
	</section>
</main>
<main class="sidebar">
	<?php $this->leftSection($category); ?>

	<section class="right">
		<h2><?= $pageType ?> Category</h2>
		<form method="post">
			<label for="name">Name</label>
			<input type="text" name="name" value="<?= $category->name ?? ''; ?>" required />
			<input type="submit" name="submit" value="<?= $pageType ?>" />
		</form>
	</section>
</main>
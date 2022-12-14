<?php
require "../include/utils.php";
createHead();
?>

<main class="home">
	<p>Welcome to Jo's Jobs, we're a recruitment agency based in Northampton. We offer a range of different office jobs. Get in touch if you'd like to list a job with us.</a></p>

	<h2>Select the type of job you are looking for:</h2>
	<ul>
		<?php foreach ($categories as $category) { ?>
			<li><a href="/admin/categories.php?id=<?= $category['id'] ?>"><?= $category['name'] ?></a></li>
		<?php } ?>
	</ul>
	</li>
</main>

<?php include '../include/footer.html.php'; ?>
<?php
require "../include/utils.php";

createHead("IT Jobs");
createHeader();
createNav();
?>

<main class="sidebar">
	<section class="left">
		<ul>
			<li><a href="it.php">IT</a></li>
			<li class="current"><a href="hr.php">Human Resources</a></li>
			<li><a href="sales.php">Sales</a></li>
		</ul>
	</section>

	<section class="right">
		<h1>Human Resources Jobs</h1>
		<ul class="listing">
			<?php
			$stmt = $pdo->prepare('SELECT * FROM job WHERE categoryId = 2 AND closingDate > :date');
			$stmt->execute(['date' => (new DateTime())->format('Y-m-d')]);

			foreach ($stmt as $job) {
				echo '<li>';

				echo '<div class="details">';
				echo '<h2>' . $job['title'] . '</h2>';
				echo '<h3>' . $job['salary'] . '</h3>';
				echo '<p>' . nl2br($job['description']) . '</p>';

				echo '<a class="more" href="/apply.php?id=' . $job['id'] . '">Apply for this job</a>';

				echo '</div>';
				echo '</li>';
			}
			?>
		</ul>
	</section>
</main>

<?php include '../include/footer.php'; ?>
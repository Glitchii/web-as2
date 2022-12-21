<?php
require "../include/utils.php";
createHead();
?>

<main class="home">
	<p>Welcome to Jo's Jobs. <a href="/about.php" class="link">Find out about us</a></p>

	<h2 style="margin-top: 40px;">Jobs expiring soonest (10 max):</h2>

	<table>
		<thead>
			<tr>
				<th>Category</th>
				<th>Title</th>
				<th>Salary</th>
			</tr>
		</thead>

		<tbody>
			<?php
			// Select up to 10 jobs unarchived and not expired -
			// ordered by closing date in ascending order to show the jobs that are closing soonest first
			$jobs = $db->job->selectAll(['archived' => 0, 'AND', 'closingDate', '>', date('Y-m-d'), 'order by closingDate asc limit 10']);
			foreach ($jobs as $job) {
				$applicantCount = $db->applicant->select(['jobId' => $job['id']], 'count(*) as count');
				$category = $db->category->select(['id' => $job['categoryId']]);
			?>
				<tr>
					<td><?= $category['name'] ?></td>
					<td><?= $job['title'] ?></td>
					<td><?= is_numeric(substr($job['salary'], 0, 1)) ? '£' . $job['salary'] : $job['salary'] ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</main>

<?php include '../include/footer.html.php'; ?>
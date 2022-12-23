<?php
require_once "../include/utils.php";

$categoryId = $_POST['category'] ?? $_GET['category'] ?? null;
createHead();
?>

<main class="home padded">
	<p>Welcome to Jo's Jobs. <a href="/about.php" class="link">Find out about us</a></p>

	<h2 style="margin-top: 40px;">Jobs expiring soonest (10 max):</h2>

	<div class="tablemenu">
		<?php require '../include/jobfilter.html.php'; ?>
	</div>

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
			// Array to select unarchived and unexpired jobs.
			$binds = ['archived' => 0, 'and', 'closingDate', '>', date('Y-m-d')];
			
			// Include category filter if set.
			if ($categoryId){
				$binds[] = 'and';
				$binds['categoryId'] = $categoryId;
			}
			
			// Order by closing date in ascending to show the jobs that are closing soonest first and limit to 10.
			$binds[] = 'order by closingDate asc limit 10';

			// Also filter by location or any locations if location is not set and search.
			$jobs = $db->job->search(['location' => $location ? "%$location%" : "%"], $binds);

			foreach ($jobs as $job) {
				$applicantCount = $db->applicant->select(['jobId' => $job['id']], 'count(*) as count');
				$category = $db->category->select(['id' => $job['categoryId']]);
			?>
				<tr>
					<td><a href="/jobs.php?jobId=<?= $job['id'] ?>"><?= $category['name'] ?></td></a>
					<td><a href="/jobs.php?jobId=<?= $job['id'] ?>"><?= $job['title'] ?></td></a>
					<td><a href="/jobs.php?jobId=<?= $job['id'] ?>"><?= is_numeric(substr($job['salary'], 0, 1)) ? '£' . $job['salary'] : $job['salary'] ?></td></a>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</main>

<?php include '../include/footer.html.php'; ?>
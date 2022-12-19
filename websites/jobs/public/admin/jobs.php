<?php
require "../../include/utils.php";

createHead("Job list");
handleLogin();

$categoryId = $_POST['category'] ?? $_GET['category'] ?? null;
$location = $_POST['location'] ?? $_GET['location'] ?? null;
?>

<main class="sidebar">
	<?php require '../../include/leftside.html.php'; ?>

	<section class="right">
		<?php
		if (!$loggedIn)
			// If user is not logged in, display log in form
			require '../../include/login-admin.html.php';
		else {
		?>
			<h2>Jobs</h2>
			<div class="tablemenu">
				<a class="new" href="addjob.php">Add new job</a>
				<detail>
					<summary>Filter category: </summary>
					<form>
						<select name="category">
							<option value="">All</option>
							<?php foreach ($categories as $category) { ?>
								<option value="<?= $category['id'] ?>" <?= $categoryId == $category['id'] ? 'selected' : '' ?>>
									<?= $category['name'] ?>
								</option>
							<?php } ?>
						</select>
						<input type="submit" value="Apply">
					</form>
				</detail>
			</div>

			<table>
				<thead>
					<tr>
						<th>Category</th>
						<th>Title</th>
						<th style="width: 15%">Salary</th>
						<th style="width: 5%">&nbsp;</th>
						<th style="width: 5%">&nbsp;</th>
						<th style="width: 15%">&nbsp;</th>
						<th style="width: 5%">&nbsp;</th>
					</tr>
				</thead>

				<tbody>
					<?php
					// Select up to 10 jobs, ordered by closing date in ascending order to show the jobs that are closing soonest first
					$querys = ['categoryId' => $categoryId, 'order by closingDate asc limit 10'];
					$jobs = $db->job->selectAll($categoryId ? $querys : $querys[0]);
					foreach ($jobs as $job) {
						$applicantCount = $db->applicant->select(['jobId' => $job['id']], 'count(*) as count');
						$category = $db->category->select(['id' => $job['categoryId']]);
					?>
						<tr>
							<td><?= $category['name'] ?></td>
							<td><?= $job['title'] ?></td>
							<td><?= is_numeric(substr($job['salary'], 0, 1)) ? '£' . $job['salary'] : $job['salary'] ?></td>
							<td><a href="editjob.php?id=<?= $job['id'] ?>">Edit</a></td>
							<?php if ($job['archived']) { ?>
								<td><a href="unarchivejob.php?id=<?= $job['id'] ?>">Unarchive</a></td>
							<?php } else { ?>
								<td><a href="archivejob.php?id=<?= $job['id'] ?>">Archive</a></td>
							<?php } ?>
							<td><a style="float: right" href="applicants.php?id=<?= $job['id'] ?>">View applicants (<?= $applicantCount['count'] ?>)</a></td>
							<td>
								<form method="post" action="deletejob.php">
									<input type="hidden" name="id" value="<?= $job['id'] ?>" />
									<input type="submit" name="submit" value="Delete" class="link" />
								</form>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		<?php } ?>
	</section>
</main>

<?php include '../../include/footer.html.php'; ?>
<?php
require "../../include/utils.php";

!$loggedIn && redirect('/admin/index.php');
$categoryId = $_POST['category'] ?? $_GET['category'] ?? null;
$location = $_POST['location'] ?? $_GET['location'] ?? null;

createHead("Job list");
// Client page
$user = $db->account->select(['id' => $_SESSION['loggedin']]);
?>

<main class="sidebar">
	<?php require '../../include/leftside.html.php'; ?>

	<section class="right">
		<h2>Jobs</h2>
		<div class="tablemenu">
			<a class="new" href="addjob.php">Add new job</a>
			<div>
				<span>Filter category: </span>
				<form>
					<select name="category">
						<option value>All</option>
						<?php foreach ($categories as $category) { ?>
							<option value="<?= $category['id'] ?>" <?= $categoryId == $category['id'] ? 'selected' : '' ?>>
								<?= $category['name'] ?>
							</option>
						<?php } ?>
					</select>
					<input type="submit" value="Apply">
				</form>
			</div>
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
				if ($categoryId)
					// Select jobs from the category specified in the URL where the $user['id'] matches the job's accountId if user isn't admin otherwise select all jobs from the category specified in the URL.
					$jobs = $db->job->selectAll($user['isAdmin'] ? ['categoryId' => $categoryId] : ['categoryId' => $categoryId, 'AND', 'accountId' => $user['id']]);
				else
					// All jobs where the $user['id'] matches the job's accountId if user isn't admin otherwise select all jobs.
					// Admins can see all jobs, clients can only see jobs they created.
					$jobs = $db->job->selectAll($user['isAdmin'] ? [] : ['accountId' => $user['id']]);

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
							<form method="post" action="deletejob.php?id=<?= $job['id'] ?>">
								<input type="submit" name="submit" value="Delete" class="link" />
							</form>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</section>
</main>

<?php include '../../include/footer.html.php'; ?>
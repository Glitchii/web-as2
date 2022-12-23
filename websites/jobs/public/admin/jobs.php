<?php
require_once "../../include/utils.php";

!$page->loggedIn() && $page->redirect('index.php');

$page->createHead("Job list");
$user = $db->account->select(['id' => $_SESSION['loggedIn']]);
?>

<main class="sidebar">
	<?php require '../../include/leftsection-staff.html.php'; ?>

	<section class="right">
		<h2>Jobs</h2>
		<div class="tablemenu">
			<a class="new" href="addjob.php">Add new job</a>
			<?php require '../../include/jobfilter.html.php'; ?>
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
				$binds = [];
				if ($categoryId)
					// Jobs in the category created by the current user or all if user is staff.
					$binds = $user['isAdmin'] ? ['categoryId' => $categoryId] : ['categoryId' => $categoryId, 'AND', 'accountId' => $user['id']];
				else
					// Above but without category filter.
					$binds = $user['isAdmin'] ? [] : ['accountId' => $user['id']];

				// Also filter by location or any locations if location is not set and search.
				$jobs = $db->job->search(['location' => $location ? "%$location%" : "%"], $binds);

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
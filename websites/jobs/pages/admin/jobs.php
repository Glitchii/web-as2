<?php
$categoryId = $this->param('category', 0);
$location = $this->param('location', 0);
$user = $this->db->account->select(['id' => $_SESSION['loggedIn']]);
?>

<main class="sidebar">
	<?php $this->leftSection($jobId, $subpage); ?>

	<section class="right">
		<h2>Jobs</h2>
		<div class="tablemenu">
			<a class="new" href="/admin/jobs/modify">Add new job</a>
			<?php $this->renderTemplate('jobfilter'); ?>
		</div>

		<table>
			<thead>
				<tr>
					<th>Category</th>
					<th>Title</th>
					<th style="width: 25%">Salary</th>
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
				$jobs = $this->db->job->search(['location' => $location ? "%$location%" : "%"], $binds);

				foreach ($jobs as $job) {
					$applicantCount = $this->db->applicant->select(['jobId' => $job['id']], 'count(*) as count');
					$category = $this->db->category->select(['id' => $job['categoryId']]);
				?>
					<tr>
						<td><?= $category['name'] ?></td>
						<td><?= $job['title'] ?></td>
						<td><?= is_numeric(substr($job['salary'], 0, 1)) ? '£' . $job['salary'] : $job['salary'] ?></td>
						<td><a href="/admin/jobs/modify?id=<?= $job['id'] ?>">Edit</a></td>
						<?php if ($job['archived']) { ?>
							<td><a href="?action=unarchive&id=<?= $job['id'] ?>">Unarchive</a></td>
						<?php } else { ?>
							<td><a href="?action=archive&id=<?= $job['id'] ?>">Archive</a></td>
						<?php } ?>
						<td><a style="float: right" href="/admin/jobs/applicants?id=<?= $job['id'] ?>">View applicants (<?= $applicantCount['count'] ?>)</a></td>
						<td><a style="float: right" href="?action=delete&id=<?= $job['id'] ?>" class="link delete" data-confirm="Are you sure you want to delete this job?">Delete</a></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</section>
</main>
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
					<th>Salary</th>
					<th>Closing Date</th>
					<th style="width: 5%">&nbsp;</th>
					<th style="width: 5%">&nbsp;</th>
					<th style="width: 15%">&nbsp;</th>
					<th style="width: 5%">&nbsp;</th>
				</tr>
			</thead>

			<tbody>
				<?php
				foreach ($jobs as $job) {
					$applicantCount = $this->db->applicant->select(['jobId' => $job['id']], 'count(*) as count');
					$category = $this->db->category->select(['id' => $job['categoryId']]);
				?>
					<tr>
						<td><?= $category['name'] ?></td>
						<td><?= $job['title'] ?></td>
						<td><?= is_numeric(substr($job['salary'], 0, 1)) ? '£' . $job['salary'] : $job['salary'] ?></td>
						<td><?= date('d/m/Y', strtotime($job['closingDate'])) ?></td>
						<td><a href="/admin/jobs/modify?id=<?= $job['id'] ?>">Edit</a></td>
						<?php if ($job['archived']) { ?>
							<td><a href="/admin/jobs/modify?id=<?= $job['id'] ?>&action=unarchive">Unarchive</a></td>
						<?php } else { ?>
							<td><a href="/admin/jobs/modify?id=<?= $job['id'] ?>&action=archive">Archive</a></td>
						<?php } ?>
						<td><a style="float: right" href="/admin/jobs/applicants?id=<?= $job['id'] ?>">View applicants (<?= $applicantCount['count'] ?>)</a></td>
						<td><a style="float: right" href="/admin/jobs/modify?id=<?= $job['id'] ?>&action=delete" class="link delete" data-confirm="Are you sure you want to delete this job?">Delete</a></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</section>
</main>
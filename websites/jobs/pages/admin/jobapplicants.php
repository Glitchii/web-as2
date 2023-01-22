<main class="sidebar">
	<?php $this->leftSection($jobId); ?>

	<section class="right">
		<h2>Applicants for <?= $this->db->job->select(['id' => $jobId])['title']; ?></h2>

		<table>
			<thead>
				<tr>
					<th style="width: 10%">Name</th>
					<th style="width: 10%">Email</th>
					<th style="width: 65%">Details</th>
					<th style="width: 15%">CV</th>
				</tr>
				<?php foreach ($this->db->applicant->selectAll(compact('jobId')) as $applicant) { ?>
					<tr>
						<td><?= $applicant['name']; ?></td>
						<td><?= $applicant['email']; ?></td>
						<td><?= $applicant['details']; ?></td>
						<td><a href="/cvs/<?= $applicant['cv']; ?>">Download CV</a></td>
					</tr>
				<?php } ?>
			</thead>
		</table>
	</section>
</main>
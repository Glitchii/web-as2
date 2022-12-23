<?php
require_once "../../include/utils.php";

!loggedIn() && redirect('index.php');
$jobId = requiredParam('id');
$db ??= new Database();

// Check if job exists and redirect to 'jobs.php' if it does not.
$job = $db->job->select(['id' => $jobId]);
!$job && redirect('jobs.php');

// Check that the current user owns the job or is staff
isOwnerOrAdmin($db, $jobId) || redirect('jobs.php');

createHead("Applicants");
?>

<main class="sidebar">
	<?php include '../../include/leftsection-staff.html.php'; ?>

	<section class="right">
		<h2>Applicants for <?= $job['title']; ?></h2>

		<table>
			<thead>
				<tr>
					<th style="width: 10%">Name</th>
					<th style="width: 10%">Email</th>
					<th style="width: 65%">Details</th>
					<th style="width: 15%">CV</th>
				</tr>
				<?php
				$applicants = $db->applicant->selectAll(['jobId' => $jobId]);
				foreach ($applicants as $applicant) { ?>
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

<?php include '../../include/footer.html.php'; ?>
<?php
require "../../include/utils.php";

!$loggedIn && exit(header('Location: jobs.php'));
$jobId = requiredParam('id');

createHead("Applicants");
handleLogin();
?>

<main class="sidebar">
	<?php include '../../include/leftside-admin.html.php'; ?>

	<section class="right">
		<?php

		$stmt = $pdo->prepare('SELECT * FROM job WHERE id = :id');
		$stmt->execute(['id' => $jobId]);
		$job = $stmt->fetch();

		// Redirect to 'jobs.php' if job doesn't exist
		!$job && exit(header('Location: jobs.php')); ?>
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

				$stmt = $pdo->prepare('SELECT * FROM applicants WHERE jobId = :id');
				$stmt->execute(['id' => $jobId]);

				foreach ($stmt as $applicant) { ?>
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
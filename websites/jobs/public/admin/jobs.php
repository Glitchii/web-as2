<?php
require "../../include/utils.php";

createHead("Job list");
handleLogin();
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
			<a class="new" href="addjob.php">Add new job</a>

			<table>
				<thead>
					<tr>
						<th>Title</th>
						<th style="width: 15%">Salary</th>
						<th style="width: 5%">&nbsp;</th>
						<th style="width: 5%">&nbsp;</th>
						<th style="width: 15%">&nbsp;</th>
						<th style="width: 5%">&nbsp;</th>
					</tr>

					<?php
					// Selecting up to 10 jobs, ordered by closing date
					// $stmt = $pdo->query('SELECT * FROM job WHERE archived = 0 ORDER BY closingDate ASC LIMIT 10');
					$stmt = $pdo->query('SELECT * FROM job ORDER BY closingDate ASC LIMIT 10');
					foreach ($stmt as $job) {
						$applicants = $pdo->prepare('SELECT count(*) as count FROM applicants WHERE jobId = :jobId');
						$applicants->execute(['jobId' => $job['id']]);
						$applicantCount = $applicants->fetch();
					?>
						<tr>
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
									<input type="submit" name="submit" value="Delete" />
								</form>
							</td>
						</tr>
					<?php } ?>
				</thead>
			</table>
		<?php } ?>
	</section>
</main>

<?php include '../../include/footer.html.php'; ?>
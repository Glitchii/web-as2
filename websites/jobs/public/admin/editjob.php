<?php
require "../../include/utils.php";

!$loggedIn && redirect('jobs.php');
$jobId = requiredParam('id');
$db ??= new Database();

// Check if job exists and redirect to 'jobs.php' if it does not.
$job = $db->job->select(['id' => $jobId]);
!$job && redirect('jobs.php');

createHead("Edit Job");
handleLogin();
?>

<main class="sidebar">
	<?php include '../../include/leftside-admin.html.php'; ?>

	<section class="right">
		<?php
		if (isset($_POST['submit'])) {
			$fields = validateJobForm($db);
			$db->job->update($fields, ['id' => $jobId]);
			echo 'Job saved';
		} else { ?> 
			<h2>Edit Job</h2>
			<form method="POST">
				<label for="title">Title</label>
				<input type="text" name="title" value="<?= $job['title']; ?>" />

				<label for="description">Description</label>
				<textarea name="description"><?= $job['description']; ?></textarea>

				<label for="location">Location</label>
				<input type="text" name="location" value="<?= $job['location']; ?>" />

				<label for="salary">Salary</label>
				<input type="text" name="salary" value="<?= $job['salary']; ?>" />

				<label for="categoryId">Category</label>
				<select name="categoryId">
					<?php foreach ($categories as $row) { ?>
						<option value="<?= $row['id']; ?>" <?= $job['categoryId'] == $row['id'] ? 'selected' : ''; ?>><?= $row['name']; ?></option>
					<?php } ?>
				</select>

				<label>Closing Date</label>
				<input type="date" name="closingDate" value="<?= $job['closingDate']; ?>" />

				<input type="submit" name="submit" value="Save" />

			</form>
		<?php  } ?>
	</section>
</main>

<?php include '../../include/footer.html.php'; ?>
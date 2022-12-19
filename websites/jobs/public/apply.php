<?php
require "../include/utils.php";

$jobId = requiredParam('id');
createHead("Apply");
?>

<main class="sidebar">
	<?php require '../include/leftside.html.php'; ?>

	<section class="right">
		<?php
		if (isset($_POST['submit'])) {
			// Check that all required fields are filled in
			$required = ['name', 'email', 'jobId', 'details'];
			foreach ($required as $field)
				if (empty($_POST[$field]))
					echo 'Please fill in all required fields';

			// Reference for error codes: https://www.php.net/manual/en/features.file-upload.errors.php#115746)
			if ($_FILES['cv']['error'] == 4)
				echo 'CV file not uploaded';
			else if ($_FILES['cv']['error'] == 1 || $_FILES['cv']['error'] == 2)
				echo 'CV file is too large';
			else if ($_FILES['cv']['error'] != 0)
				echo 'There was an error uploading your CV';
			else {
				$parts = explode('.', $_FILES['cv']['name']);
				$extension = end($parts);
				$fileName = uniqid() . '.' . $extension;

				move_uploaded_file($_FILES['cv']['tmp_name'], 'cvs/' . $fileName);

				$db->applicant->insert([
					'name' => $_POST['name'],
					'email' => $_POST['email'],
					'details' => $_POST['details'],
					'jobId' => $_POST['jobId'],
					'cv' => $fileName
				]);

				echo 'Your application is complete. We will contact you after the closing date.';
			}
		} else {
			$job = $db->job->select(['id' => $jobId, 'AND', 'archived' => 0]);
		?>
			<h2>Apply for <?= $job['title']; ?></h2>

			<form method="POST" enctype="multipart/form-data">
				<label>Your name</label>
				<input type="text" name="name" required />

				<label>E-mail address</label>
				<input type="text" name="email" required />

				<label>Cover letter</label>
				<textarea name="details" required></textarea>

				<label>CV</label>
				<input type="file" name="cv" required />

				<input type="hidden" name="jobId" value="<?= $job['id']; ?>" required />

				<input type="submit" name="submit" value="Apply" />

			</form>
		<?php } ?>
	</section>
</main>

<?php include '../include/footer.html.php'; ?>
<?php
require "../../include/utils.php";

!$loggedIn && exit(header('Location: jobs.php'));
$jobId = requiredParam('id');

createHead("Edit Job");
handleLogin();
?>

<main class="sidebar">
	<?php include '../../include/leftside-admin.php'; ?>

	<section class="right">
		<?php
		if (isset($_POST['submit'])) {
			$pdo->prepare('UPDATE job SET title = :title, description = :description, salary = :salary, location = :location, categoryId = :categoryId, closingDate = :closingDate WHERE id = :id')
				->execute([
					'title' => $_POST['title'],
					'description' => $_POST['description'],
					'salary' => $_POST['salary'],
					'location' => $_POST['location'],
					'categoryId' => $_POST['categoryId'],
					'closingDate' => $_POST['closingDate'],
					'id' => $jobId
				]);

			echo 'Job saved';
		} else {
			$stmt = $pdo->prepare('SELECT * FROM job WHERE id = :id');
			$stmt->execute(['id' => $jobId]);
			$job = $stmt->fetch();

			// Job should exists, otherwise redirect to jobs.php
			!$job && exit(header('Location: jobs.php')); ?>

			<h2>Edit Job</h2>
			<form action="editjob.php" method="POST">
				<input type="hidden" name="id" value="<?= $job['id']; ?>" />
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
					<?php
					$stmt = $pdo->prepare('SELECT * FROM category');
					$stmt->execute();

					foreach ($stmt as $row)
						echo "<option value='{$row['id']}' " . ($job['categoryId'] == $row['id'] ? 'selected' : '') . ">{$row['name']}</option>";
					?>
				</select>

				<label>Closing Date</label>
				<input type="date" name="closingDate" value="<?= $job['closingDate']; ?>" />

				<input type="submit" name="submit" value="Save" />

			</form>
		<?php  } ?>
	</section>
</main>

<?php include '../../include/footer.php'; ?>
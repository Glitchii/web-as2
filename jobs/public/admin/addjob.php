<?php
require "../../include/utils.php";

!$loggedIn && exit(header('Location: jobs.php'));

createHead("Add Job");
createHeader();
createNav();
handleLogin();
?>

<main class="sidebar">
	<?php include '../../include/admin-left.php'; ?>

	<section class="right">
		<?php if (isset($_POST['submit'])) {
			$stmt = $pdo->prepare('INSERT INTO job (title, description, salary, location, closingDate, categoryId) VALUES (:title, :description, :salary, :location, :closingDate, :categoryId)');
			$stmt->execute([
				'title' => $_POST['title'],
				'description' => $_POST['description'],
				'salary' => trim($_POST['salary']),
				'location' => $_POST['location'],
				'categoryId' => $_POST['categoryId'],
				'closingDate' => $_POST['closingDate'],
			]);

			echo 'Job Added';
		} else { ?>
			<h2>Add Job</h2>
			<form method="POST">
				<label>Title</label>
				<input type=" text" name="title" />

				<label>Description</label>
				<textarea name="description"></textarea>

				<label>Salary</label>
				<input type="text" name="salary" />

				<label>Location</label>
				<input type="text" name="location" />

				<label>Category</label>

				<select name="categoryId">
					<?php foreach ($pdo->query('SELECT * FROM category') as $row) { ?>
						<option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
					<?php } ?>
				</select>

				<label>Closing Date</label>
				<input type="date" name="closingDate" />

				<input type="submit" name="submit" value="Add" />
			</form>
		<?php } ?>
	</section>
</main>

<?php include '../../include/footer.php'; ?>
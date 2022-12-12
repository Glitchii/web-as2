<?php
require "../../include/utils.php";

!$loggedIn && exit(header('Location: jobs.php'));

createHead("Add Job");
handleLogin();
?>

<main class="sidebar">
	<?php include '../../include/leftside-admin.php'; ?>

	<section class="right">
		<?php if (isset($_POST['submit'])) {
			// Web request can be altered outside a browser form, so we need to validate the data.
			// Check if a required field is empty
			$required = ['title', 'description', 'salary', 'location', 'categoryId', 'closingDate'];
			foreach ($required as $field)
				if (empty($_POST[$field]))
					exit("The '$field' field is required.");

			// Salary does not to be a number, it can have a range eg. 20,000 - 30,000, currency symbols,
			// And other stuff the user might want to add, eg. command or information about the salary eg. "negotiable".
			// And since a feature to sort jobs by salary is not asked for, we don't need to validate the salary field.

			// Check if closing date is in the future
			if (strtotime($_POST['closingDate']) < time())
				exit("Closing date must be in the future.");

			// Check if category exists
			$stmt = $pdo->prepare('SELECT * FROM category WHERE id = :id');
			$stmt->execute(['id' => $_POST['categoryId']]);
			if (!$stmt->fetch())
				exit("Category does not exist.");
			
			// Finally add the job
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
				<label for="title">Title</label>
				<input name="title" type="text" required/>

				<label>Description</label>
				<textarea name="description" type="text" required></textarea>

				<label for="salary">Salary</label>
				<input name="salary" type="text" required/>

				<label for="location">Location</label>
				<input name="location" type="text" required/>

				<label for="categoryId">Category</label>
				<select name="categoryIdrequired">
					<?php foreach ($pdo->query('SELECT * FROM category') as $row) { ?>
						<option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
					<?php } ?>
				</select>

				<label for="closingDate">Closing Date</label>
				<input name="closingDate" type="date" required/>

				<input name="submit" type="submit" value="Add" />
			</form>
		<?php } ?>
	</section>
</main>

<?php include '../../include/footer.php'; ?>
<?php
require "../../include/utils.php";

!$loggedIn && redirect('/admin/index.php');
createHead("Add Job");
// Client page
?>

<main class="sidebar">
	<?php include '../../include/leftside-admin.html.php'; ?>

	<section class="right">
		<?php if (isset($_POST['submit'])) {
			// validateJobForm() exits with error message if form is invalid.
			// It returns an array of fields with values from the form if valid.
			$fields = validateJobForm($db);
			// Insert the new job into the database
			$db->job->insert($fields);

			echo 'Job Added';
		} else { ?>
			<h2>Add Job</h2>
			<form method="POST">
				<label for="title">Title</label>
				<input name="title" type="text" required />

				<label>Description</label>
				<textarea name="description" type="text" required></textarea>

				<label for="salary">Salary</label>
				<input name="salary" type="text" required />

				<label for="location">Location</label>
				<input name="location" type="text" required />

				<label for="categoryId">Category</label>
				<select name="categoryId">
					<?php foreach ($categories as $row) { ?>
						<option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
					<?php } ?>
				</select>

				<label for="closingDate">Closing Date</label>
				<input name="closingDate" type="date" required />

				<input name="submit" type="submit" value="Add" />
			</form>
		<?php } ?>
	</section>
</main>

<?php include '../../include/footer.html.php'; ?>
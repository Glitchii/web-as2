<?php
$pageType = $jobId ? 'Edit' : 'Add';
$job = $jobId ? $this->db->job->select(['id' => $jobId]) : [];
$jobId && !$job && $this->redirect('/admin/jobs');
?>

<main class="sidebar">
	<?php $this->leftSection($jobId); ?>

	<section class="right">
		<h2><?= $pageType; ?> Job</h2>
		<form method="post">
			<label for="title">Title</label>
			<input type="text" name="title" value="<?= $job->title ?? ''; ?>" />

			<label for="description">Description</label>
			<textarea name="description"><?= $job->description ?? ''; ?></textarea>

			<label for="location">Location</label>
			<input type="text" name="location" value="<?= $job->location ?? ''; ?>" />

			<label for="salary">Salary</label>
			<input type="text" name="salary" value="<?= $job->salary ?? ''; ?>" />

			<label for="categoryId">Category</label>
			<select name="categoryId">
				<?php foreach ($this->categories as $row) { ?>
					<option value="<?= $row->id; ?>" <?= ($job->categoryId ?? '') == $row->id ? 'selected' : ''; ?>><?= $row->name; ?></option>
				<?php } ?>
			</select>

			<label>Closing Date</label>
			<input type="date" name="closingDate" value="<?= $job->closingDate ?? ''; ?>" />

			<input type="submit" name="submit" value="<?= $pageType; ?>" />
		</form>
	</section>
</main>
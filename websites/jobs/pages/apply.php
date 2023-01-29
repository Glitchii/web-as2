<main class="sidebar">
	<section class="left">
		<ul>
			<li><a href="/jobs">Other Jobs</a></li>
			<hr>
			<li><a href="/">Back Home</a></li>
		</ul>
	</section>

	<section class="right">
		<?php if ($output) { ?>
			<p class="message"><?= $output ?></p>
		<?php } else { ?>
			<h2>Apply for '<?= $job->title; ?>'</h2>
			<form method="post" enctype="multipart/form-data">
				<label>Your name</label>
				<input type="text" name="name" required />

				<label>E-mail address</label>
				<input type="text" name="email" required />

				<label>Cover letter</label>
				<textarea name="details" required></textarea>

				<label>CV</label>
				<input type="file" name="cv" required />

				<input type="hidden" name="jobId" value="<?= $job->id; ?>" required />
				<input type="hidden" name="applying" value="true" />

				<input type="submit" name="submit" value="Apply" />
			</form>
		<?php } ?>
	</section>
</main>
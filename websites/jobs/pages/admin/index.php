<main class="sidebar">
	<?php $this->renderTemplate('leftsection-staff'); ?>

	<section class="right">
		<?php if ($this->isStaff()) { ?>
			<h2>Dashboard</h2>
			<p>Welcome to the admin dashboard. Here you can manage all jobs, categories, and accounts.</p>
		<?php } else { ?>
			<h2>Dashboard</h2>
			<p>Welcome to the client dashboard. Here you can manage all jobs you created and see all applicants.</p>
		<?php } ?>
		<p>Start by selecting an option from the sidebar on the left.</p>
	</section>
</main>
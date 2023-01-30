<main class="padded">
	<h2>Contact Us</h2>
	<p><?= $this->handleSubmit(); ?></p>
	<form method="post">
		<label for="name">Name</label>
		<input type="text" name="name" id="name" required>
		<label for="email">Email</label>
		<input type="email" name="email" id="email" required>
		<label for="telephone">Telephone</label>
		<input type="tel" name="telephone" id="telephone" required>
		<label for="enquiry">Enquiry</label>
		<textarea name="enquiry" id="enquiry" required></textarea>
		<input type="submit" name="submit" value="submit">
	</form>
</main>
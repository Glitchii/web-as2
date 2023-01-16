<?php
require_once "../utils/utils.php";

$page->createHead("Contact");

function handleSubmit($db) {
	if (!isset($_POST['submit']))
		return 'For any enquiries, please contact us using the form below.';

	$name = $_POST['name'] ?? null;
	$email = $_POST['email'] ?? null;
	$telephone = $_POST['telephone'] ?? null;
	$enquiry = $_POST['enquiry'] ?? null;

	if (!($name && $email && $telephone && $enquiry))
		return 'Please fill in all fields.';

	// Using regular expressions to check if number is valid.
	// Personal numbers should start with a 0 or a + (followed by a country code) then 9 numbers after 7.
	if (!preg_match('/^(\+\d|0)7\d{9}$/', $telephone))
		// I validate through PHP instead of using HTML input's pattern attribute because while it would stop the
		// user from submitting if the pattern does not match, it would not tell the user why it is not valid or
		// what the correct format is.
		return 'Phone number should start with 0 or country code (eg. +44) followed by 10 numbers.';

	// Use regular expressions to check if email is valid.
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		// ref https://www.php.net/manual/en/filter.examples.validation.php
		return 'Please enter a valid email address.';

	// Check if enquiry is valid.
	if (strlen($enquiry) < 10)
		return 'Enquiry must be at least 10 characters long.';

	$db->enquiry->insert([
		'name' => $name,
		'email' => $email,
		'telephone' => $telephone,
		'enquiry' => $enquiry,
		'accountId' => $_SESSION['loggedIn'],
	]);

	return 'Enquiry sent. Got something else to say? Fill in the form below.';
}
?>

<main class="padded">
	<h2>Contact Us</h2>
	<p><?= handleSubmit($db) ?></p>
	<form action="contact.php" method="post">
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

<?php include '../templates/footer.html.php'; ?>
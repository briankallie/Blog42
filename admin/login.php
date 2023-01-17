<?php
# Set timezone to your location
ini_set("date.timezone", "America/Chicago");

$error = '';
if (isset($_POST['login'])) { // If Log in button is clicked
	session_start(); // Start user session
	$username = trim($_POST['username']); // Trim username input
	$password = trim($_POST['pwd']); // Trim password input

	// location to redirect on success
	$redirect = '/admin/index.php';
require_once('../includes/authenticate_mysqli.php');
}

# Create a human friendly name based on file name.
include("../includes/title-page-name.php");

# The header
include("../includes/header.php");
?>

<main>
	<section class="column two-thirds float-left">
		<h2>Login</h2>
		<?php
		if ($error) {
			echo "<p>$error</p>";
		} elseif (isset($_GET['expired'])) {
			?>
			<p class="error">Your session has expired. Please <a href="/admin/login.php">log in</a> again.</p>
		<?php } ?>
		<form id="loginform" method="post">
			<fieldset>
				<legend>Login</legend>
				<ol>
					<li>
						<label for="username">Username:</label>
						<input type="text" name="username" id="username">
					</li>
					<li>
						<label for="pwd">Password:</label>
						<input type="password" name="pwd" id="pwd">
					</li>
					<li>
						<input name="login" type="submit" id="login" value="Log in">
					</li>
				</ol>
			</fieldset>
		</form>
	</section><!-- /two-thirds -->

<?php
# The sidebar
include("../includes/sidebar-guest.php");
?>
</main>

<?php
# The footer
include("../includes/footer.php");
?>
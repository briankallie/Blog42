<?php
# Set timezone to your location
ini_set("date.timezone", "America/Chicago");

if (isset($_POST['register'])) { // If register button is clicked...
  $username = trim($_POST['username']); // Trim left and right spaces from username
  $password = trim($_POST['pwd']); // Trim left and right spaces from pwd
  $retyped = trim($_POST['conf_pwd']); // Trim left and right spaces from conf_pwd
  require_once('../../includes/register_user_mysqli.php');
}

# Create a human friendly name based on file name.
include("../../includes/title-page-name.php");

# The header
include("../../includes/header.php");
?>

<main>
	<section class="column two-thirds float-left">
		<h2>Register</h2>
		<?php
            if (isset($success)) {
              echo "<p>$success</p>";
            } elseif (isset($errors) && !empty($errors)) {
              echo '<ul class="error">';
              foreach ($errors as $error) {
                echo "<li >$error</li>";
              }
              echo '</ul>';
            }
            ?>
            <form id="form1" method="post" novalidate>
                <fieldset>
                    <legend><?php echo $title_page_name; ?></legend>
                    <ol>
                        <li>
                            <label for="username">Username:</label>
                            <input type="text" name="username" id="username" required>
                          </li>
                          <li>
                            <label for="pwd">Password:</label>
                            <input type="password" name="pwd" id="pwd" required>
                          </li>
                          <li>
                            <label for="conf_pwd">Confirm password:</label>
                            <input type="password" name="conf_pwd" id="conf_pwd" required>
                          </li>
                          <li>
                            <input name="register" type="submit" id="register" value="Register">
                          </li>
                    </ol>
                </fieldset>
            </form>
	</section><!-- /two-thirds -->

<?php
# The sidebar
include("../../includes/sidebar-guest.php");
?>
</main>

<?php
# The footer
include("../../includes/footer.php");
?>
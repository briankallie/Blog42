<?php
# Set timezone to your location
ini_set("date.timezone", "America/Chicago");

require_once('../includes/session_timeout.php');
require_once('../includes/connection.php');

// redirect if $_GET['about_us_id'] not defined
if (!isset($_GET['about_us_id']) || $_GET['about_us_id'] != 1) {
  header('Location: /admin/index.php');
  exit;
}

// create database connection
$conn = dbConnect('write');

// get details of selected record
if (isset($_GET['about_us_id']) && !$_POST) {
    // initialize flags
    $OK = false;
    $done = false;
    
    // initialize statement
    $stmt = $conn->stmt_init();
    
  // prepare SQL query
  $sql = 'SELECT about_us_id, about_us_title, about_us_article
          FROM fp_about_us WHERE about_us_id = ?';
  if ($stmt->prepare($sql)) {
    // bind the query parameter
    $stmt->bind_param('i', $_GET['about_us_id']);
    // bind the results to variables
    $stmt->bind_result($about_us_id, $about_us_title, $about_us_article);
    // execute the query, and fetch the result
    $OK = $stmt->execute();
    $stmt->fetch();
    $stmt->free_result();
  }
}

if(isset($_POST['about_update'])) {
	$stmt = $conn->stmt_init();

    // check for empty fields
    $error = '';
    if(($_POST['about_us_title']) == ''){
        $error = "Please enter a title. ";
        $errorTitle = true;
    }
    elseif(($_POST['about_us_article']) == ''){
        $error = "Please enter a description. ";
        $errorArticle = true;
    }
    elseif((($_POST['about_us_article']) == '') && ($_POST['about_us_title']) == '') {
        $error = "Please enter a title and description. ";
        $errorArticle = true;
        $errorTitle = true;
    } 
    else {
        $sql2 = 'UPDATE fp_about_us SET about_us_title = ?, about_us_article = ?
        WHERE about_us_id = ?';
        $stmt->prepare($sql2);
        $stmt->bind_param('ssi', $_POST['about_us_title'], $_POST['about_us_article'], $_POST['about_us_id']);
        $stmt->execute();

        if($stmt->affected_rows == 1) {
            //a record was updated set $OK to true
            $OK = $stmt->affected_rows;
        }
        if($stmt->errno === 0) {
            //nothing was changed no record was updated set $OK to true
            $OK = 1;
        }
        // redirect if successful or display error
        if ($OK) {
            header('Location: /about-us/index.php');
            exit;
        }
    }
}

# Create a human friendly name based on file name.
include("../includes/title-page-name.php");

# The header
include("../includes/header.php");
?>

<main>
	<section class="column two-thirds float-left">
		<h2>Edit About Us</h2>
		<?php 
		if (isset($error)) {
			echo "<p class='error'>Error: $error</p>";
		}
		if(1 == 0) { ?>
			<p class="error">Invalid request: record does not exist.</p>
		<?php } else { ?>
			<form id="form1" method="post">
				<fieldset>
					<legend>Edit About Us</legend>

					<ol>
						<li <?php if(isset($errorTitle)) echo 'class="error"'?>>
							<label for="about_us_title">Description Title:</label>
							<input name="about_us_title" type="text" class="widebox" id="about_us_title" value="<?php 
							if (isset($error)) {
								echo htmlentities($_POST['about_us_title'], ENT_COMPAT, 'utf-8');
								} else {
									echo htmlentities($about_us_title, ENT_COMPAT, 'utf-8');
								}
								?>">
							</li>
							<li <?php if(isset($errorArticle)) echo 'class="error"'?>>
								<label for="about_us_article">About Us Description:</label>
								<textarea name="about_us_article" cols="60" rows="8" class="widebox" id="about_us_article"><?php 
								if (isset($error)) {
									echo htmlentities($_POST['about_us_article'], ENT_COMPAT, 'utf-8');
								} else {
									echo htmlentities($about_us_article, ENT_COMPAT, 'utf-8');
								}
								?></textarea>
							</li>
								<li>
									<input type="submit" name="about_update" value="Update About Us description">
									<input name="about_us_id" type="hidden" value="<?php if(isset($about_us_id)) { echo $about_us_id; } else { echo $_POST['about_us_id']; } ?>">
								</li>
							</ol>
						</fieldset>
					</form>

				<?php } ?>
				<?php include('../includes/logout.php'); ?>
			</section><!-- /two-thirds -->
<?php
# The sidebar
include("../includes/sidebar-admin.php");
?>
		</main>

<?php

# The footer
include("../includes/footer.php");
?>
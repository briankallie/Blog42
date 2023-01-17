<?php
# Set timezone to your location
ini_set("date.timezone", "America/Chicago");

require_once('../includes/session_timeout.php');
require_once('../includes/connection.php');

$conn = dbConnect('write');

// initialize flags
$OK = false;
$deleted = false;

// initialize statement
$stmt = $conn->stmt_init();

// get details of selected record
if (isset($_GET['article_id']) && !$_POST) {
  // prepare SQL query              DATE_FORMAT(updated, '%W, %M %D, %Y')
  $sql = 'SELECT article_id, title, DATE_FORMAT(created, "%W, %M %D, %Y") AS created
          FROM fp_blog WHERE article_id = ?';

  if ($stmt->prepare($sql)) {
    // bind the query parameters
    $stmt->bind_param('i', $_GET['article_id']);
    // bind the result to variables
    $stmt->bind_result($article_id, $title, $created);
    // execute the query, and fetch the result
    $stmt->execute();
    $stmt->fetch();
  }
}

// if confirm deletion button has been clicked, delete record
if (isset($_POST['delete'])) {
  $sql = 'DELETE FROM fp_blog WHERE article_id = ?';
  if ($stmt->prepare($sql)) {
    $stmt->bind_param('i', $_POST['article_id']);
    $stmt->execute();
    // if there's an error affected_rows is -1
    if ($stmt->affected_rows > 0) {
      $deleted = true;
    } else {
      $error = 'There was a problem deleting the record. '; 
    }
  }
}

// redirect the page if deletion is successful, 
// cancel button clicked, or $_GET['article_id'] not defined
if ($deleted || isset($_POST['cancel_delete']) || !isset($_GET['article_id']))  {
  header('Location: /admin/index.php');
  exit;
}

// if any SQL query fails, display error message
if (isset($stmt) && !$OK && !$deleted) {
  if(isset($error)) {
      $error .= $stmt->error;
  }
}

# Create a human friendly name based on file name.
include("../includes/title-page-name.php");

# The header
include("../includes/header.php");
?>

<main>
	<section class="column two-thirds float-left">
		<h2>Delete Post</h2>
		<?php 
            if (isset($error)  && !empty($error)) {
              echo "<p class='error'>Error: $error</p>";
            }
            if($article_id == 0) { ?>
            <p class="error">Invalid request: record does not exist.</p>
            <?php } else { ?>
            <h2 class="error">Please confirm that you want to delete the following item.<br>Note: This action cannot be undone.</h2>
            <ul>
            	<li>Title: <?php echo htmlentities($title, ENT_COMPAT, 'utf-8'); ?></li>
                <li>Date: <?php echo $created; ?></li>
            </ul>
            <?php } ?>
            <form id="form1" method="post">
                <fieldset>
                    <legend>Delete Post</legend>
                    
                    <ol>
                        <li>
                        <?php if(isset($article_id) && $article_id > 0) { ?>
                            <input type="submit" name="delete" value="Confirm Deletion">
                        <?php } ?>
                            <input name="cancel_delete" type="submit" id="cancel_delete" value="Cancel">
                        <?php if(isset($article_id) && $article_id > 0) { ?>
                            <input name="article_id" type="hidden" value="<?php echo $article_id; ?>">
                        <?php } ?>
                        </li>
                    </ol>
                </fieldset>
            </form>
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
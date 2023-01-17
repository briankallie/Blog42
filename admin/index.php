<?php
# Set timezone to your location
ini_set("date.timezone", "America/Chicago");

# Database connection and blog session timeout includes
require_once('../includes/session_timeout.php');
require_once('../includes/connection.php');

# Create database connection
$conn = dbConnect('read');
$sql = 'SELECT *, DATE_FORMAT(created, "%a, %b %D, %Y") AS date_created FROM fp_blog ORDER BY created DESC';
$result = $conn->query($sql) or die(mysqli_error()); // Run query or throw error

# Number of records found
$numRows = $result->num_rows;

# Create a human friendly name based on file name.
include("../includes/title-page-name.php");

# The header
include("../includes/header.php");
?>

<main>
	<section class="column two-thirds float-left">
		<h2>Blog Listings</h2>

    <?php
    # If no records found, display message
      if ($numRows == 0) {
    ?>
    <p>No records found =(</p>
    <?php
    # Otherwise, display the results
      } else {
    ?>
    <table>
      <tr>
        <th scope="col">Created</th>
        <th scope="col">Title</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
      <?php while($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?php echo $row['date_created']; ?></td>
        <td><?php echo $row['title']; ?></td>
        <td><a href="edit.php?article_id=<?php echo $row['article_id']; ?>">EDIT</a></td>
        <td><a href="delete.php?article_id=<?php echo $row['article_id']; ?>">DELETE</a></td>
      </tr>
      <?php } ?>
    </table>

    <?php
    # Close the else clause wrapping the results table
      }
    ?>

<?php 
include('../includes/logout.php');
?>
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
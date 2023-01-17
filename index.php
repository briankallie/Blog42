<?php
# Set timezone to your location
ini_set("date.timezone", "America/Chicago");

# Included database connection and utility functions for blog
require_once('includes/connection.php');
require_once('includes/utility_funcs.php');

# create database connection
# #DATE_FORMAT(created, "%W &mdash; %e %M %Y") AS created_date 
$conn = dbConnect('read'); // Connect in Read Only mode
$sql = 'SELECT *, 
        DATE_FORMAT(created, "%W &mdash; %e %M %Y") AS created_date
        FROM fp_blog
        LEFT JOIN fp_images
        USING (image_id)
        ORDER BY created DESC'; //sort by created 20xx-xx-xx (not the alias)
$result = $conn->query($sql);

# Create a human friendly name based on file name.
include("includes/title-page-name.php");

# The header
include("includes/header.php");
?>

<main>
	<section class="column two-thirds float-left">
		<h2>Recent thoughts and ponderings...</h2>
    <?php 
      while ($row = $result->fetch_assoc()) { // While there are still results...
      // clean db content
      $c_title = htmlentities($row['title'], ENT_COMPAT, 'utf-8');
      $c_article = htmlentities($row['article'], ENT_COMPAT, 'utf-8');
      $c_caption = htmlentities($row['caption'], ENT_COMPAT, 'utf-8');
    ?>
    <div>
      <h2><?php echo $c_title; ?>
        <span><?php echo $row['created_date']; ?></span>
      </h2>
      <p>
        <?php 
        // If an image file exists and is included in the blog post...
        if ( ($row['filename']) && (file_exists ($_SERVER['DOCUMENT_ROOT'] . '/images/thumbs/' . $row['filename'])) ) { 
          echo "<img src=\"/images/thumbs/{$row['filename']}\" alt=\"{$c_caption}\">"; // ... Echo the image thumb/caption
        }
        ?>
        
        <?php 
        $extract = getFirst($row['article'], 2); // Get first sentence from the article field
        
        echo htmlentities($extract[0], ENT_COMPAT, 'utf-8'); // Sanitize output
        if ($extract[1]) { 
          // If article is bigger than 2nd parameter in getFirst function...
          echo '<a href="detail.php?article_id=' .$row['article_id']. '">Read More&hellip;</a>'; // Get article ID and output Read More... link
        } 
        ?>
      </p>
    </div>
    <?php } ?>
  </section><!-- /two-thirds -->

<?php
# The sidebar
include("includes/sidebar-guest.php");
?>
</main>

<?php
# The footer
include("includes/footer.php");
?>
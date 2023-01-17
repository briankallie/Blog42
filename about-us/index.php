<?php
# Set timezone to your location
ini_set("date.timezone", "America/Chicago");

# Included database connection and utility functions for blog
require_once('../includes/connection.php');
require_once('../includes/utility_funcs.php');

# create database connection
$conn = dbConnect('read'); // Connect in Read Only mode

# Get About Us page info.
$sql = 'SELECT * FROM fp_about_us WHERE about_us_id = 1'; //sort by created 20xx-xx-xx (not the alias)
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$about_us_article = $row['about_us_article'];

# Get random blog post
$sql2 = 'SELECT *, 
        DATE_FORMAT(created, "%W &mdash; %e %M %Y") AS created_date 
        FROM fp_blog 
        LEFT JOIN fp_images
        USING (image_id) ORDER BY RAND() LIMIT 1';
$result2 = $conn->query($sql2);
$rand_post = $result2->fetch_assoc();

# Create a human friendly name based on file name.
include("../includes/title-page-name.php");

# The header
include("../includes/header.php");
?>

<main>
  <section class="column two-thirds float-left">

    <h2><?php echo $row['about_us_title'] ?></h2>
    <?php echo convertToParas($about_us_article) ?>
    <hr>
    <?php
    // clean db content
    $c_title = htmlentities($rand_post['title'], ENT_COMPAT, 'utf-8');
    $c_article = htmlentities($rand_post['article'], ENT_COMPAT, 'utf-8');
    $c_caption = htmlentities($rand_post['caption'], ENT_COMPAT, 'utf-8');
    ?>
    <h2>
      <?php echo $c_title; ?>
      <span><?php echo $rand_post['created_date']; ?></span>
    </h2>
    <hr>
    <p>
<?php // If an image file exists and is included in the blog post...
if ( ($rand_post['filename']) && (file_exists ($_SERVER['DOCUMENT_ROOT'] . '/images/thumbs/' . $rand_post['filename'])) ) { 
echo "<img src=\"/images/thumbs/{$rand_post['filename']}\" alt=\"{$c_caption}\">"; // ... Echo the image thumb/caption
}
?>

<?php 
$extract = getFirst($rand_post['article'], 2); // Get first sentence from the article field

echo htmlentities($extract[0], ENT_COMPAT, 'utf-8'); // Sanitize output
if ($extract[1]) { // If article is bigger than 2nd parameter in getFirst function...
echo '<a href="/detail.php?article_id=' .$rand_post['article_id']. '">Read More&hellip;</a>'; // Get article ID and output Read More... link
} 
?>
</p>
<hr>
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
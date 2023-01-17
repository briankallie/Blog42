<?php
# Set timezone to your location
ini_set("date.timezone", "America/Chicago");

require_once('includes/utility_funcs.php');
require_once('includes/connection.php');

// Connect to the database
$conn = dbConnect('read');

// Check for article_id in query string
if (isset($_GET['article_id']) && is_numeric($_GET['article_id'])) {
  $article_id = (int) $_GET['article_id'];
} else {
  $article_id = 0;
}

$sql = "SELECT title, article, DATE_FORMAT(updated, '%W, %M %D, %Y') AS updated, filename, caption
        FROM fp_blog 
        LEFT JOIN fp_images 
        USING (image_id)
        WHERE fp_blog.article_id = $article_id";
        
$result = $conn->query($sql);
$row = $result->fetch_assoc();

# Create a human friendly name based on file name.
include("includes/title-page-name.php");
$title_page_name = $row['title'];

# The header
include("includes/header.php");
?>

<main>
  <section class="column two-thirds float-left">
    <h2>
      <?php 
      if ($row) {;
        echo $row['title'];
        echo "<span>" . $row['updated'] . "</span>";
      } else {
        echo 'No record found';
      }
      ?>
    </h2>

    <?php
    if ($row && !empty($row['filename'])) {
      $filename = "/images/{$row['filename']}";
      $imageSize = getimagesize($_SERVER['DOCUMENT_ROOT'] . $filename);
      ?>

      <figure><a href="<?php echo $filename; ?>"><?php #DO NOT MOVE ANCHOR ELEMENT, IT MUST STAY ON THIS LINE! ?>
        <img src="<?php echo $filename; ?>" alt="<?php echo $row['caption']; ?>" <?php echo $imageSize[3]; ?>>
        </a><figcaption><?php echo $row['caption']; ?></figcaption>
      </figure>
    <?php } if ($row) { 
      $htmleArticle = htmlentities($row['article'], ENT_COMPAT, 'utf-8');
      echo convertToParas($htmleArticle);
      # echo convertToParas($htmleArticle);
    } ?>
    <p><a href="/">Back to the home page</a></p>
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
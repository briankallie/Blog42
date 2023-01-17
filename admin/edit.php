<?php
# Set timezone to your location
ini_set("date.timezone", "America/Chicago");

require_once('../includes/session_timeout.php');
require_once('../includes/connection.php');

// redirect if $_GET['article_id'] not defined
if (!isset($_GET['article_id'])) {
  header('Location: /admin/index.php');
  exit;
}

// create database connection
$conn = dbConnect('write');

// get details of selected record
if (isset($_GET['article_id']) && !$_POST) {
    // initialize flags
    $OK = false;
    $done = false;
    
    // initialize statement
    $stmt = $conn->stmt_init();
    
  // prepare SQL query
  $sql = 'SELECT article_id, image_id, title, article
          FROM fp_blog WHERE article_id = ?';
  if ($stmt->prepare($sql)) {
    // bind the query parameter
    $stmt->bind_param('i', $_GET['article_id']);
    // bind the results to variables
    $stmt->bind_result($article_id, $image_id, $title, $article);
    // execute the query, and fetch the result
    $OK = $stmt->execute();
    $stmt->fetch();
    $stmt->free_result();
  }
}

if(isset($_POST['insert'])) {
    // check for empty fields
    $error = '';
    if(($_POST['title']) == ''){
        $error = "Please enter a title. ";
        $errorTitle = true;
    }
    
    if(($_POST['article']) == ''){
        $error = "Please enter an article. ";
        $errorArticle = true;
    }
    if((($_POST['article']) == '') && ($_POST['title']) == '') {
        $error = "Please enter a title and an article. ";
        $errorArticle = true;
        $errorTitle = true;
    }
}


use PhpSolutions\Image\ThumbnailUpload;
$max = 512000; // 512000/1024/100 = 5MB

if ( (isset($_POST['insert'])) && ($error === '') ) {
  // initialize flag
  $OK = false;
  // initialize prepared statement
  $stmt = $conn->stmt_init();
  
  // if a file has been uploaded, process it
  if(isset($_POST['upload_new']) && $_FILES['image']['error'] == 0) {
    $imageOK = false;
    
    require_once('../PhpSolutions/Image/ThumbnailUpload.php');
    try {
        $loader = new ThumbnailUpload($_SERVER['DOCUMENT_ROOT'] . '/images/');
        $loader->setThumbDestination($_SERVER['DOCUMENT_ROOT'] . '/images/thumbs/');
        $loader->setMaxSize($max);
        $loader->setThumbSuffix('');
        $loader->upload();
        // after uploading and creating the thumbnail
        // get the name of the image
        // must add new lines to the processFile method in the ThumbnailUpload class see page 438
        // new lines will add file name to the filenames array
        // must add new property to the Upload class to store the filename $_filenames
        // must add new method to the Upload class to retrieve the filename getFilenames
        $names = $loader->getFilenames();
        // now $names contains an array with the names of the uploaded images (note: we are only uploading a single image)
        $messages = $loader->getMessages();
    } catch (Exception $e) {
        $errors = $e->getMessage();
    }
    
    // $names will be an empty array if the upload failed
    if ($names) {
      $sql = 'INSERT INTO fp_images (filename, caption)
              VALUES (?, ?)';
      $stmt->prepare($sql);
      $stmt->bind_param('ss', $names[0], $_POST['caption']);
      $stmt->execute();
      $imageOK = $stmt->affected_rows;
    }
    // get the image's primary key or find out what went wrong
    if ($imageOK) {
      $image_id = $stmt->insert_id;
    } else {
      $imageError = implode(' ', $loader->getMessages());
    }
  } elseif (isset($_POST['image_id']) && !empty($_POST['image_id'])) {
    // get the primary key of a previously uploaded image from the select menu choice
    $image_id = $_POST['image_id'];
  }

  // don't insert blog details if the image failed to upload
  if (!isset($imageError)) {
    // if $image_id has been set, insert it as a foreign key
    if (isset($image_id)) {
      $sql = 'UPDATE fp_blog SET image_id = ?, title = ?, article = ?
               WHERE article_id = ?';
      $stmt->prepare($sql);
      $stmt->bind_param('issi', $image_id, $_POST['title'], $_POST['article'], $_POST['article_id'] );
    } else {
      $sql = 'UPDATE fp_blog SET image_id = NULL, title = ?, article = ?
              WHERE article_id = ?';
      $stmt->prepare($sql);
      $stmt->bind_param('ssi', $_POST['title'], $_POST['article'], $_POST['article_id'] );
    }
    // execute and get number of affected rows
    $stmt->execute();
    // Test to see if we updated a record or no changes were made
    if($stmt->affected_rows == 1) {
        //a record was updated set $OK to true
        $OK = $stmt->affected_rows;
    }
    if($stmt->errno === 0) {
        //nothing was changed no record was updated set $OK to true
        $OK = 1;
    }
  }

  // redirect if successful or display error
  if ($OK && !isset($imageError)) {
    header('Location: /admin/index.php');
    exit;
  } else {
    $error = $stmt->error;
    if (isset($imageError)) {
      $error .= ' ' . $imageError;
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
		<h2>Edit Post</h2>
		<?php 
		if (isset($error)) {
			echo "<p class='error'>Error: $error</p>";
		}
		if(1 == 0) { ?>
			<p class="error">Invalid request: record does not exist.</p>
		<?php } else { ?>
			<form id="form1" method="post" enctype="multipart/form-data">
				<fieldset>
					<legend>Edit Post</legend>

					<ol>
						<li <?php if(isset($errorTitle)) echo 'class="error"'?>>
							<label for="title">Title:</label>
							<input name="title" type="text" class="widebox" id="title" value="<?php 
							if (isset($error)) {
								echo htmlentities($_POST['title'], ENT_COMPAT, 'utf-8');
								} else {
									echo htmlentities($title, ENT_COMPAT, 'utf-8');
								}
								?>">
							</li>
							<li <?php if(isset($errorArticle)) echo 'class="error"'?>>
								<label for="article">Article:</label>
								<textarea name="article" cols="60" rows="8" class="widebox" id="article"><?php 
								if (isset($error)) {
									echo htmlentities($_POST['article'], ENT_COMPAT, 'utf-8');
								} else {
									echo htmlentities($article, ENT_COMPAT, 'utf-8');
								}
								?></textarea>
							</li>
							<li>
								<label for="image_id">Uploaded image:</label>
								<select name="image_id" id="image_id">
									<option value="">Select image</option>
									<?php
									// get the list of images
									$getImages = 'SELECT image_id, filename
									FROM fp_images ORDER BY filename';
									$images = $conn->query($getImages);
									while ($row = $images->fetch_assoc()) {
										?>
										<option value="<?php echo $row['image_id']; ?>"
											<?php
											if (isset($_POST['image_id']) && $row['image_id'] == $_POST['image_id']) {
												echo 'selected';
											}
											if (isset($image_id) && $row['image_id'] == $image_id) {
												echo 'selected';
											}
											?>><?php echo $row['filename']; ?></option>
										<?php } ?>
									</select>
								</li>
								<li id="allowUpload">
									<label for="upload_new">
										<input type="checkbox" name="upload_new" id="upload_new">
									Upload new image</label>
								</li>
								<li class="optional">
									<label for="image">Select image:</label>
									<input type="file" name="image" id="image">
								</li>
								<li class="optional">
									<label for="caption">Caption:</label>
									<input name="caption" type="text" class="widebox" id="caption">
								</li>
								<li>
									<input type="submit" name="insert" value="Update Blog Entry">
									<input name="article_id" type="hidden" value="<?php if(isset($article_id)) { echo $article_id; } else { echo $_POST['article_id']; } ?>">
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
// Set $js variable so that toggle_fields.js file is loaded in footer
$js = '<script src="/js/toggle-fields.js"></script>';

# The footer
include("../includes/footer.php");
?>
<?php
# Set timezone to your location
ini_set("date.timezone", "America/Chicago");

# Create a human friendly name based on file name.
include("includes/title-page-name.php");

# The header
include("includes/header.php");
?>

<main>
  <section class="column two-thirds float-left">
    <h2>Whoops!</h2>
    <p>Oh dear, looks like that page/directory doesn't exist. Or perhaps you're not allowed to see it.</p>
    <p>To compensate this inconvenience, have a <a href="/">free link back to the home page.</a></p>
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
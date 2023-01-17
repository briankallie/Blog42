<?php
# Set timezone to your location
ini_set("date.timezone", "America/Chicago");

# Create a human friendly name based on file name.
include("../includes/title-page-name.php");

# The header
include("../includes/header.php");
?>

<main>
  <section class="column two-thirds float-left">
    <h2>Thank You!</h2>
    <h3>Thank you for contacting us</h3>
        <p>Expect a reply from us within 72 hours.</p>
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
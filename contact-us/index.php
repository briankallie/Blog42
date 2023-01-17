<?php
# Set timezone to your location
ini_set("date.timezone", "America/Chicago");

# Include reCAPTCHA Library and keys
require_once('../includes/google-recaptcha-library.php');
$g_recaptcha_key_site = '6LdPlvIjAAAAAAh7cc3nPD1EdqUTx3evcBykhnqF';
$g_recaptcha_key_secret = '6LdPlvIjAAAAABOXL15SiCM6E2CsWlWiJcGgUrS_';

# Add reCAPTCHA JS to header include
$js_head = '<script src="https://www.google.com/recaptcha/api.js"></script>';

# Email Validation and Sending
$errors = [];
$missing = [];

// check if the form has been submitted
if (isset($_POST['send'])) {
	// email processing script
	$to = 'brian@briankallie.com';
	$subject = 'You have a new message from Blog42!';

	// list expected fields
	$expected = array('name', 'email', 'comments');

	// set required fields
	$required = array('name', 'email', 'comments');

	// create additional headers
	$headers = "From: Brian Kallie<mail@briankallie.com>\r\n";
	$headers .= 'Content-Type: text/plain; charset=utf-8';

    // Place results of g_recaptcha_request() in $g_recaptcha_response
    $g_recaptcha_response = g_recaptcha_request();

    if (!$g_recaptcha_response->success) {
      $errors['recaptcha'] = true;
    }

// nothing is suspicious...yet
$suspect = false;

// pattern to locate suspicious phrases
$pattern = '/Content-Type:|Bcc:|Cc:/i';

// function to check for suspicious phrases
function isSuspect($val, $pattern, &$suspect) {
    // if the variable is an array, loop through each element
    // and pass it recursively back to the same function
    if (is_array($val)) {
        foreach ($val as $item) {
            isSuspect($item, $pattern, $suspect);
        }
    } else {
        // if one of the suspect phrases is found, set Boolean to true
        if (preg_match($pattern, $val)) {
            $suspect = true;
        }
    }
}
// check the $_POST array and any subarrays for suspect content
isSuspect($_POST, $pattern, $suspect);

if (!$suspect) {
    foreach ($_POST as $key => $value) {
        // assign to temporary variable and strip whitespace if not an array
        // ternary operator pattern - a one line if/else statement
        // condition to test ? Do this if true : Do this if false
        $temp = is_array($value) ? $value : trim($value);
        // if empty and required, add to $missing array
        if (empty($temp) && in_array($key, $required)) {
            $missing[] = $key;
            ${$key} = '';
        } elseif (in_array($key, $expected)) {
            // otherwise, assign to a variable of the same name as $key
            ${$key} = $temp;
        }
    }
}
// validate the user's email
if (!$suspect && !empty($email)) {
    $validemail = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if ($validemail) {
        $headers .= "\r\nReply-To: $validemail";
    } else {
        $errors['email'] = true;
    }
}

$mailSent = false;

// go ahead only if not suspect, all required fields OK, and no errors
if (!$suspect && !$missing && !$errors) {
    // initialize the $message variable
    $message = '';

    // loop through the $expected array
    foreach($expected as $item) {
        // assign the value of the current item to $val
        if (isset(${$item}) && !empty(${$item})) {
            $val = ${$item};
        } else {
            // if it has no value, assign 'Not selected'
            $val = 'Not selected';
        }

        // if an array, implode into comma-separated string
        if (is_array($val)) {
            $val = implode(', ', $val);
        }

        // replace underscores and hyphens in the label with spaces
        $item = str_replace(['_', '-'], ' ', $item);

        // add label and value to the message body
        $message .= ucfirst($item).": $val\r\n\r\n";
    }

    // limit line length to 70 characters
    $message = wordwrap($message, 70);
    $mailSent = mail($to, $subject, $message, $headers);

    if (!$mailSent) {
        $errors['mailfail'] = true;
    }
} // close: if not: suspect/missing errors
    
# END: include require processmail

    if ($mailSent) {
        header('Location: thank-you.php');
        exit;
    }

} // close: if (isset($_POST['send']))


# Create a human friendly name based on file name.
include("../includes/title-page-name.php");

# The header
include("../includes/header.php");
?>

<main>
  <section class="column two-thirds float-left">
    <h2>Contact Us</h2>

        <?php if (($_POST && $suspect) || ($_POST && isset($errors['mailfail']))) {  ?>
            <p class="error">Sorry, your mail could not be sent.  Please try later.</p>
            <?php } elseif ($missing || $errors) { ?>
            <p class="error">Please fix the item(s) indicated.</p>
            <?php } ?>
            
            <form id="feedback" method="post" action="">
                <fieldset>
                    <legend>Contact Us</legend>
                    <ol>
                        <li <?php if ($missing && in_array('name', $missing)) echo 'class="error"'; ?>>
                            <?php if ($missing && in_array('name', $missing)) { ?>
                              <strong>Please enter your name</strong>
                            <?php } ?>
                            <label for="name">Name:</label>
                            <input name="name" id="name" type="text" class="formbox"
                            <?php if (isset($name)) { 
                             echo 'value="' . htmlentities($name, ENT_COMPAT, 'UTF-8') . '"';
                            } ?>>
                        </li>
                        <li <?php if (($missing && in_array('email', $missing)) || (isset($errors['email']))) echo 'class="error"'; ?>>
                            <?php if ($missing && in_array('email', $missing)) { ?>
                              <strong>Please enter your email</strong>
                            <?php } elseif (isset($errors['email'])) { ?>
                              <strong>Invalid email address</strong>
                            <?php } ?>
                            <label for="email">Email:</label>
                            <input name="email" id="email" type="text" class="formbox"
                            <?php if (isset($email)) { 
                             echo 'value="' . htmlentities($email, ENT_COMPAT, 'UTF-8') . '"';
                            } ?>>
                        </li>
                        <li <?php if ($missing && in_array('comments', $missing)) echo 'class="error"'; ?>>
                            <?php if ($missing && in_array('comments', $missing)) { ?>
                              <strong>Please enter your comments</strong>
                            <?php } ?>
                            <label for="comments">Comments:</label>
                            <textarea name="comments" id="comments" cols="60" rows="8"><?php
                            if (isset($comments)) {
                                echo htmlentities($comments, ENT_COMPAT, 'UTF-8');
                            } ?></textarea>
                        </li>
                        <li <?php if (isset($errors['recaptcha'])) echo 'class="error"'; ?>>
                            <?php if (isset($errors['recaptcha'])) { ?>
                            <strong>Incorect Response</strong>
                            <?php }
                            echo "<label>Answer Challenge Question</label>";
                            echo g_recaptcha_get_form_control(); ?>
                        </li>
                        <li>
                            <input name="send" id="send" type="submit" value="Send message">
                        </li>    
                    </ol>
                </fieldset>
            </form>
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
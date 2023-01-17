<?php
session_start();
ob_start();

$timelimit = 15 * 60; // set a time limit in seconds (15 Minutes)
$now = time(); // get the current time
$redirect = '/admin/login.php'; // where to redirect if rejected

// if session variable not set, redirect to login page
if (!isset($_SESSION['authenticated'])) {
    header("Location: $redirect");
    exit;
} elseif ($now > $_SESSION['start'] + $timelimit) {
    // if time limit has expired, destroy session and redirect
    $_SESSION = [];

    // invalidate the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-86400, '/');
    }
    // end session and redirect with query string
    session_destroy();
    header("Location: {$redirect}?expired=yes");
    exit;
} else {
    // if it's got this far, it's OK, so update start time
    $_SESSION['start'] = time();
}

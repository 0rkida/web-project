<?php
session_start();

// Set session timeout (in seconds)
$timeout = 900; // 15 minutes

// Check if the user was active
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout)) {
    // Last activity was more than 15 minutes ago, destroy the session
    session_unset();
    session_destroy();
    header("Location: logout.html"); // Redirect to a logout page
    exit();
}

// Update last activity timestamp
$_SESSION['LAST_ACTIVITY'] = time();
?>

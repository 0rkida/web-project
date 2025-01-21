<?php

// sessionManager.php

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set the session timeout duration in seconds (15 minutes = 900 seconds)
$sessionTimeout = 120; // 15 minutes

// Check if the session has already been started and calculate the time since the last activity
if (isset($_SESSION['LAST_ACTIVITY'])) {
    $lastActivity = $_SESSION['LAST_ACTIVITY'];
    $currentTime = time();
    $timeSinceLastActivity = $currentTime - $lastActivity;

    // Check if the session has exceeded the timeout duration
    if ($timeSinceLastActivity > $sessionTimeout) {
        // Session expired, destroy the session
        session_unset();
        session_destroy();
        header('Location: /login'); // Redirect to login page
        exit();
    } else {
        // Update the last activity time to the current time
        $_SESSION['LAST_ACTIVITY'] = $currentTime;
    }
} else {
    // Set the last activity time for the session if not already set
    $_SESSION['LAST_ACTIVITY'] = time();
}


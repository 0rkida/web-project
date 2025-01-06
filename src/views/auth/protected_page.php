<?php

require 'db.php';
require 'User.php';
require 'verify.php';

// Secure session functions
function secureSessionStart() {
    $sessionName = 'secure_session';  // Custom session name
    $secure = true;  // Use secure cookies (if using HTTPS)
    $httponly = true;  // Restrict cookies to HTTP only

    // Set session cookie parameters
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',  // You can set the domain if needed
        'secure' => $secure,
        'httponly' => $httponly
    ]);

    session_name($sessionName);
    session_start();  // Start the session
}

function checkSessionTimeout() {
    $timeout_duration = 1800;  // 30 minutes
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout_duration)) {
        session_unset();     // Unset session variables
        session_destroy();   // Destroy session
        header("Location: login.php"); // Redirect to login page
        exit();
    }
    $_SESSION['last_activity'] = time(); // Update last activity time
}
// Your protected page content here
class protected_page {
    public function __construct() {
        secureSessionStart(); // Starts the session securely
        checkSessionTimeout(); // Check if the session has timed out

        // If the user is not logged in, redirect to login page
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }
    }

    // Display the protected page content
    public function displayContent() {
        // Example content, replace with actual protected page content
        echo "Welcome to the protected page, user!";
        echo "<br>";
        echo "User ID: " . $_SESSION['user_id'];
        echo "<br>";
        echo "Role: " . $_SESSION['role'];
    }

    // Optionally, you can add more methods for other functionalities, like logging out
    public function logout() {
        session_unset();     // Unset session variables
        session_destroy();   // Destroy session
        header("Location: login.php"); // Redirect to login page
        exit();
    }
}

// Instantiate the class
$page = new ProtectedPage();
$page->displayContent();


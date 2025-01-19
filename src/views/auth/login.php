<?php
session_start();
use App\models\Admin;
use App\models\User;

require_once '../../db.php';  // Assuming db.php contains the database connection
require_once 'C:\xampp\htdocs\web-project\src\models\User.php';
require_once 'C:\xampp\htdocs\web-project\src\models\Admin.php';
require_once '../../services/LoginService.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']);

    // Check if the user is blocked
    if (User::isBlocked($email)) {
        $_SESSION['error_message'] = "Your account is temporarily locked due to multiple failed login attempts. Please try again after 30 minutes.";
        header("Location: /login.php");
        exit();
    }

    // First, check if it's an admin login
    $admin = Admin::authenticateAdmin($email, $password);

    if ($admin) {
        // Admin login successful
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['role'] = 'Admin';
        $_SESSION['last_activity'] = time();

        // Handle "Remember Me" for admin
        if ($rememberMe) {
            $token = bin2hex(random_bytes(16)); // Generate a random token
            setcookie("remember_me", $token, time() + (86400 * 30), "/");
            Admin::saveRememberMeToken($admin['id'], $token);
        }

        // Redirect to the admin dashboard
        header("Location: /admin/dashboard.php");
        exit();
    } else {
        // Check for regular user login
        $user = User::authenticateUser($email, $password);

        if ($user) {
            // User login successful
            // Reset failed login attempts for user
            User::resetFailedAttempts($email);

            // Start session for user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['last_activity'] = time();

            // Handle "Remember Me" for user
            if ($rememberMe) {
                $token = bin2hex(random_bytes(16)); // Generate a random token
                setcookie("remember_me", $token, time() + (86400 * 30), "/");
                User::saveRememberMeToken($user['id'], $token);
            }

            // Redirect to the user dashboard
            header("Location: /user/dashboard.php");
            exit();
        } else {
            // Incorrect login credentials
            // Increment failed login attempts for user
            User::incrementFailedAttempts($email);

            // Set the error message in session for display in modal
            $_SESSION['error_message'] = "Invalid email or password.";
            header("Location: /login.php");
            exit();
        }
    }
}

// Check if there's an error message and pass it to JavaScript
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Unset after passing to JS
    echo "<script>
            document.getElementById('error-message').innerText = '$error_message';
            document.getElementById('error-message').style.display = 'block';
          </script>";
} else {
    $error_message = '';
}

// Include the login form HTML from the separate file
include('public/login.html');
?>
<?php
session_start();
use App\models\Admin;
use App\models\User;

require_once '../../db.php';  // Assuming db.php contains the database connection
require_once 'C:\xampp\htdocs\web-project\src\models\User.php';
require_once 'C:\xampp\htdocs\web-project\src\models\Admin.php';
require_once '../../services/LoginService.php';

include('auth_check.php');
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
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
            $token = bin2hex(random_bytes(32)); // Generate a random token
            $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));

            // Save token in the database
            Admin::saveRememberMeToken($admin['id'], $token, $expiry);

            // Set cookie
            setcookie("remember_me", $token, time() + (86400 * 30), "/", "", false, true);
        }

        // Redirect to the admin dashboard
        header("Location: /admin/dashboard.php");
        exit();
    } else {
        // Check for regular user login
        $user = User::authenticateUser($email, $password);

        if ($user) {
            // User login successful
            User::resetFailedAttempts($email); // Reset failed login attempts for user

            // Start session for user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['last_activity'] = time();

            // Handle "Remember Me" for user
            if ($rememberMe) {
                $token = bin2hex(random_bytes(32)); // Generate a random token
                $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));

                // Save token in the database
                User::saveRememberMeToken($user['id'], $token, $expiry);

                // Set cookie
                setcookie("remember_me", $token, time() + (86400 * 30), "/", "", false, true);
            }

            // Redirect to the user dashboard
            header("Location: /user/dashboard.php");
            exit();
        } else {
            // Incorrect login credentials
            User::incrementFailedAttempts($email); // Increment failed login attempts for user

            // Set the error message in session for display in modal
            $_SESSION['error_message'] = "Invalid email or password.";
            header("Location: /login.php");
            exit();
        }
    }
}

// Include the login form HTML
include('public/login.html');

// Display error message if set
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Unset after use
    echo "<script>
            document.getElementById('error-message').innerText = '$error_message';
            document.getElementById('error-message').style.display = 'block';
          </script>";
}
?>

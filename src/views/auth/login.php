<?php

use App\models\Admin;
use App\models\User;

session_start();
require_once '../../db.php';  // Assuming db.php contains the database connection
require_once 'C:\xampp\htdocs\web-project\src\models\User.php';
require_once 'C:\xampp\htdocs\web-project\src\models\Admin.php';
require_once '../../services/LoginService.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']);

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

            if (User::isBlocked($email)) {
                echo "Your account is temporarily locked due to multiple failed login attempts.";
            } else {
                echo "Invalid email or password.";
            }
        }
    }
}

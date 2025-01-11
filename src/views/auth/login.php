<?php
session_start();
require_once '../../db.php';  // Assuming db.php contains the database connection
require_once 'C:\xampp\htdocs\web-project\src\models\User.php';
require_once '../../services/LoginService.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']);

    // Authenticate user
    $user = User::authenticateUser($email, $password);

    if ($user) {
        // Reset failed login attempts
        User::resetFailedAttempts($email);

        // Start session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();

        // Handle "Remember Me"
        if ($rememberMe) {
            $token = bin2hex(random_bytes(16)); // Generate a random token
            setcookie("remember_me", $token, time() + (86400 * 30), "/");
            User::saveRememberMeToken($user['id'], $token);
        }

        // Redirect to role-based page
        header("Location: ../dashboard.php");
        exit();
    } else {
        // Increment failed login attempts
        User::incrementFailedAttempts($email);

        if (User::isBlocked($email)) {
            echo "Llogaria është bllokuar për 30 minuta.";
        } else {
            echo "Email ose fjalëkalim i pasaktë.";
        }
    }
}

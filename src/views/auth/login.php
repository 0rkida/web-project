<?php
require 'Database.php';
require 'User.php';
require 'EmailVerification.php';
require 'Verification.php';

include 'database.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']);

    $user = User::authenticate($email, $password);
    if ($user) {
        // Reset failed login attempts
        User::resetFailedAttempts($email);

        // Start session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();

        // Handle "Remember Me"
        if ($rememberMe) {
            $token = bin2hex(random_bytes(16));
            setcookie("remember_me", $token, time() + (86400 * 30), "/");
            User::saveRememberMeToken($user['id'], $token);
        }

        // Redirect to role-based page
        header("Location: dashboard.php");
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

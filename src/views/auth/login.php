<?php
require 'Database.php';
require 'User.php';
require 'verify.php';

include 'Database.php';

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

    if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
        $remember_token = $_COOKIE['remember_me'];

        // Query the database to find a user with this token
        $query = "SELECT * FROM users WHERE remember_token = :remember_token LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":remember_token", $remember_token);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Token is valid, log the user in
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
        }
    }

}

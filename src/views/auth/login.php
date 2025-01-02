<?php
require 'Database.php';
require 'User.php';
require 'verify.php';

include 'Database.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = User::authenticate($email, $password);
    if ($user) {
        loginUser($user['id'], $user['role']);
        redirectToRoleBasedPage();
    } else {
        echo "Invalid email or password.";
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

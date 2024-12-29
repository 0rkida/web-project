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

    $user = User::authenticate($email, $password);
    if ($user) {
        loginUser($user['id'], $user['role']);
        redirectToRoleBasedPage();
    } else {
        echo "Invalid email or password.";
    }
}

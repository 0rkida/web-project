<?php
require 'Database.php';
require 'User.php';
require 'Verification.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email already exists
    $conn = Database::getConnection();
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "Email already registered.";
    } else {
        // Register the user
        $verificationCode = EmailVerification::generateVerificationCode();
        $sql = "INSERT INTO users (username, email, password, verification_code) VALUES ('$username', '$email', '$password', '$verificationCode')";
        if (mysqli_query($conn, $sql)) {
            EmailVerification::sendVerificationEmail($email, $verificationCode);
            echo "Registration successful! Please check your email to verify your account.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

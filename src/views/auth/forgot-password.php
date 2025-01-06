<?php
require 'db.php';
require 'verify.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $conn = Database::getConnection();

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $resetToken = bin2hex(random_bytes(32));
        $resetTokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store the reset token and expiry in the database
        $sql = "UPDATE users SET reset_token = '$resetToken', reset_token_expiry = '$resetTokenExpiry' WHERE email = '$email'";
        mysqli_query($conn, $sql);

        // Send the reset link to the user's email
        $resetLink = "https://yourdomain.com/reset_password.php?token=$resetToken";
        EmailVerification::sendVerificationEmail($email, $resetLink);

        echo "A password reset link has been sent to your email.";
    } else {
        echo "No account found with that email address.";
    }
}
?>
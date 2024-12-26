<?php
class User {
    public static function register($userData) {
        $conn = Database::getConnection();
        $verificationCode = EmailVerification::generateVerificationCode();
        $userEmail = $userData['email'];
        // Other user data...

        // Insert user data and verification code into the database
        $sql = "INSERT INTO users (email, verification_code, ...) VALUES ('$userEmail', '$verificationCode', ...)";
        mysqli_query($conn, $sql);

        // Send verification email
        EmailVerification::sendVerificationEmail($userEmail, $verificationCode);
    }
}
?>
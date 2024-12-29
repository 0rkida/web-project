<?php
// Include database connection
global $conn;
include '../includes/db.php';

// Function to generate a random verification code
function generateVerificationCode() {
    return bin2hex(random_bytes(32)); // Generate a 64-character random string
}

// Handle user registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verificationCode = generateVerificationCode();

    // Hash the password (BCRYPT recommended)
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user data along with verification code into the database
    $stmt = $conn->prepare("INSERT INTO users (email, password, verification_code, is_verified) VALUES (?, ?, ?, 0)");
    $stmt->bind_param("sss", $email, $hashedPassword, $verificationCode);

    if ($stmt->execute()) {
        // Send verification email
        sendVerificationEmail($email, $verificationCode);
        echo "Registration successful! A verification code has been sent to your email.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Function to send verification email
function sendVerificationEmail($email, $verificationCode) {
    $subject = "Email Verification Code";
    $message = "Your verification code is: $verificationCode";
    $headers = "From: no-reply@yourdomain.com";

    mail($email, $subject, $message, $headers);
}


<?php

// Database connection function
function getDbConnection() {
    $conn = new mysqli('localhost', 'username', 'password', 'database');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    return $conn;
}

// Function to fetch user ID by email
function getUserIdByEmail($conn, $email) {

    global $user_id;
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($user_id); // Corrected variable name here
    $stmt->fetch();
    $stmt->close();
    return $user_id;
}

// Function to insert reset token
function insertResetToken($conn, $user_id, $token, $expiry) {
    $stmt = $conn->prepare("INSERT INTO password_resets (user_id, reset_token, reset_token_expiry, remember_token) VALUES (?, ?, ?, '')");
    $stmt->bind_param('iss', $user_id, $token, $expiry);
    $stmt->execute();
    $stmt->close();
}

// Function to send reset email
function sendResetEmail($email, $resetLink) {
    $subject = 'Password Reset';
    $message = 'Click the following link to reset your password: ' . $resetLink;
    $headers = 'From: holtaozuni12@gmail.com' . "\r\n" .
        'Reply-To: no-reply@yourdomain.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    mail($email, $subject, $message, $headers);
}

// Main logic
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    echo 'Email received: ' . $email . '<br>';

    $token = bin2hex(random_bytes(50));
    $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));
    $conn = getDbConnection();

    $user_id = getUserIdByEmail($conn, $email);
    echo 'User ID fetched: ' . $user_id . '<br>';

    if ($user_id !== null) {
        insertResetToken($conn, $user_id, $token, $expiry);
        $resetLink = 'http://yourdomain.com/reset-password.php?token=' . $token;
        sendResetEmail($email, $resetLink);
        echo 'Password reset link has been sent to your email.';
    } else {
        echo 'No user found with that email address.';
    }

    $conn->close();
}

<?php
// Include necessary files and start session
require 'C:\xampp\htdocs\web-project\vendor\autoload.php';

// Database connection
$conn = new mysqli("localhost", "root", "root", "test");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle verification
if (isset($_GET['email']) && isset($_POST['verify_code'])) {
    $email = trim($_GET['email']);
    $verifyCode = trim($_POST['verify_code']);

    // Query to validate the verification code
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND verification_code = ?");
    $stmt->bind_param("ss", $email, $verifyCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Mark user as verified using 'is_verified'
        $updateStmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE email = ?");
        $updateStmt->bind_param("s", $email);

        if ($updateStmt->execute()) {
            echo "Your account has been successfully verified!";
            echo "<a href='../src/views/auth/login.php'>Click here to log in</a>";
        } else {
            echo "Error updating verification status. Please try again later.";
        }
    } else {
        echo "Invalid verification code or email. Please try again.";
    }


}else {
    echo "Invalid request. Please check your email for the verification link.";
}

// HTML form for entering verification code
if (isset($_GET['email'])) {
    $email = htmlspecialchars($_GET['email']);
    echo "<h2>Verify Your Email</h2>
        <form method='POST'>
            <p>Enter the verification code sent to <strong>$email</strong>:</p>
            <input type='text' name='verify_code' placeholder='Enter code' required />
            <button type='submit'>Verify</button>
        </form>";
}

// Close database connection
$conn->close();

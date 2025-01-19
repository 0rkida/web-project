<?php
// Include necessary files
global $db;
global $conn;
require '../../db.php';  // Ensure your DB connection is correctly set here
require '../../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    // Validate the token
    // TODO: Add your database connection code here

    $sql = "SELECT * FROM password_resets WHERE token='$token'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];

        // Update the user's password
        $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password='$newPasswordHash' WHERE email='$email'";
        if (mysqli_query($conn, $sql)) {
            // Delete the reset token
            $sql = "DELETE FROM password_resets WHERE token='$token'";
            mysqli_query($conn, $sql);
            echo "Your password has been successfully reset.";
        }
    } else {
        echo "Invalid or expired token.";
    }
} else {
    $token = $_GET['token'];
}



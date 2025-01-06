<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $conn = Database::getConnection();

    // Check if the token is valid and not expired
    $sql = "SELECT * FROM users WHERE reset_token = '$token' AND reset_token_expiry > NOW()";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Update the user's password
        $sql = "UPDATE users SET password = '$newPassword', reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = '$token'";
        mysqli_query($conn, $sql);

        echo "Your password has been reset successfully.";
    } else {
        echo "Invalid or expired token.";
    }
}
?>
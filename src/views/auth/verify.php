<?php
global $conn;
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $verificationCode = $_POST['verification_code'];

    // Fetch the verification code from the database
    $stmt = $conn->prepare("SELECT verification_code, is_verified FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($storedVerificationCode, $isVerified);
    $stmt->fetch();

    if ($storedVerificationCode === $verificationCode && !$isVerified) {
        // Update to mark the user as verified
        $updateStmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE email = ?");
        $updateStmt->bind_param("s", $email);

        if ($updateStmt->execute()) {
            echo "Email verified successfully!";
        } else {
            echo "Verification failed: " . $updateStmt->error;
        }

        $updateStmt->close();
    } else {
        echo "Invalid or expired verification code.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
</head>
<body>
    <form action="verify.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="verification_code">Verification Code:</label>
        <input type="text" name="verification_code" required>

        <button type="submit">Verify</button>
    </form>
</body>
</html>


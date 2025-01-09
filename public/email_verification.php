<?php
if (isset($_GET['email']) && isset($_GET['code'])) {
    $email = $_GET['email'];
    $verificationCode = $_GET['code'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $inputCode = $_POST['verification_code'];

        // Database connection
        $conn = mysqli_connect("localhost", "root", "root", "test");

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Check if verification code matches in database
        $query = "SELECT * FROM users WHERE email = '$email' AND verification_code = '$verificationCode' AND email_verified_at IS NULL";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // Verification successful
            $updateQuery = "UPDATE users SET email_verified_at = NOW() WHERE email = '$email'";
            if (mysqli_query($conn, $updateQuery)) {
                echo "Email verified successfully!";
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        } else {
            echo "Invalid verification code or email.";
        }

        mysqli_close($conn);
    } else {
        // Show the verification form
        echo '<form method="POST" action="">
            <input type="text" name="verification_code" placeholder="Enter the verification code" required />
            <input type="submit" value="Verify" />
        </form>';
    }
} else {
    echo "Invalid request.";
}
?>

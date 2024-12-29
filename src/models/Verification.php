<?php
class Verification {
    public static function verifyEmail($verificationCode) {
        $conn = Database::getConnection();

        // Check if the code exists in the database
        $sql = "SELECT * FROM users WHERE verification_code = '$verificationCode'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Code is valid, update user status
            $sql = "UPDATE users SET is_verified = 1 WHERE verification_code = '$verificationCode'";
            mysqli_query($conn, $sql);
            echo "Email verified successfully!";
        } else {
            echo "Invalid verification code.";
        }
    }
}
//
//require 'Database.php';
//
//if (isset($_GET['code'])) {
//    $verificationCode = htmlspecialchars(trim($_GET['code']));
//    $conn = Database::getConnection();
//
//    $stmt = $conn->prepare("SELECT * FROM users WHERE verification_code = ?");
//    $stmt->bind_param("s", $verificationCode);
//    $stmt->execute();
//    $result = $stmt->get_result();
//
//    if ($result->num_rows === 1) {
//        $stmt = $conn->prepare("UPDATE users SET verified = 1 WHERE verification_code = ?");
//        $stmt->bind_param("s", $verificationCode);
//        if ($stmt->execute()) {
//            echo "Email verified successfully!";
//        } else {
//            echo "Error verifying email.";
//        }
//    } else {
//        echo "Invalid verification code.";
//    }
//}
//?>

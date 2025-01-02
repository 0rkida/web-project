<?php
global $conn;
class Verification
{
    public static function verifyEmail($verificationCode)
    {
        global $conn;
        self::getConnection();

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

    private static function getConnection()
    {
    }

}

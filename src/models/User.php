<?php
namespace App\models;
use AllowDynamicProperties;
use Exception;

#[AllowDynamicProperties]
class User
{
    private $dbConnection;

    public function __construct($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    // Registers the user in the database
    public function registerUser($email,$full_name, $username, $password, $verificationCode)
    {
        // Check if the email already exists
        if ($this->isEmailTaken($email)) {
            return false;
        }

        //$verificationCode = $this->generateVerificationCode();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert user data into the database
        $stmt = $this->dbConnection->prepare("INSERT INTO users (email, full_name,username, password, verification_code, is_verified) VALUES (?, ?,?, ?, ?, 0)");
        $stmt->bind_param("sssss", $email,$full_name,$username, $hashedPassword, $verificationCode,);

        return $stmt->execute();
    }

    // Authenticates the user by checking email and password
    public function authenticateUser($email, $password)
    {
        $stmt = $this->dbConnection->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                return $user['id']; // User is authenticated
            }
        }

        return false; // Invalid email or password
    }

    // Fetches user data by user ID
    public function getUserById($userId)
    {
        $stmt = $this->dbConnection->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0 ? $result->fetch_assoc() : null; // Return user data or null
    }

    // Generates a random verification code
    public function generateVerificationCode()
    {
        try {
            return bin2hex(random_bytes(32));
        } catch (Exception $e) {
            die('Unable to generate verification code: ' . $e->getMessage());
        }
    }

    // Verifies the user using the verification code
    public function verifyUser($verificationCode)
    {
        $stmt = $this->dbConnection->prepare("UPDATE users SET is_verified = 1 WHERE verification_code = ?");
        $stmt->bind_param("s", $verificationCode);
        return $stmt->execute();
    }

    // Checks if a user is verified by email
    public function isUserVerified($userId): bool
    {
        error_log("emaili duke u loguar:". $userId);
        $stmt = $this->dbConnection->prepare("SELECT is_verified FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 && $result->fetch_assoc()['is_verified'] == 1;
    }

    // Checks if the verification code matches for the email
    public function checkVerificationCode($email, $code): bool
    {
        $stmt = $this->dbConnection->prepare("SELECT * FROM users WHERE email = ? AND verification_code = ?");
        $stmt->bind_param("ss", $email, $code);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // Marks the user as verified using the email
    public function markUserAsVerified($email)
    {
        $stmt = $this->dbConnection->prepare("UPDATE users SET is_verified = 1 WHERE email = ?");
        $stmt->bind_param("s", $email);
        return $stmt->execute();
    }

    // Checks if the email is already taken
    private function isEmailTaken($email): bool
    {
        $stmt = $this->dbConnection->prepare("SELECT 1 FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function saveResetToken($email, $token, $expiry)
    {
        $stmt = $this->dbConnection->prepare("UPDATE password_resets SET reset_token = ?, reset_token_expiry = ? WHERE user_id = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        return $stmt->execute();
    }

    public function verifyResetToken($token): bool
    {
        $stmt = $this->dbConnection->prepare("SELECT * FROM password_resets where reset_token_expiry =? ");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function updatePassword($token, $hashedPassword)
    {
        $stmt = $this->dbConnection->prepare("UPDATE password_resets SET reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
        $stmt->bind_param("ss", $hashedPassword, $token);
        return $stmt->execute();
    }

    // Reset failed login attempts
    public static function resetFailedAttempts($email): bool
    {
        global $db;
        $stmt = $db->prepare("UPDATE login_attempts SET attempt_time = 0 WHERE user_id = ?");
        $stmt->bind_param("s", $email);
        return $stmt->execute();
    }

    // Save Remember Me Token
    public static function saveRememberMeToken($userId, $token): bool
    {
        global $db;
        $stmt = $db->prepare("UPDATE password_resets SET reset_token = NULL WHERE id = ?");
        $stmt->bind_param("si", $token, $userId);
        return $stmt->execute();
    }

    // Increment failed login attempts
    public static function incrementFailedAttempts($email): bool
    {
        global $db;

        // Prepare the SQL query to update the last failed attempt for the given email
        $stmt = $db->prepare("UPDATE login_attempts SET last_failed_attempt = NOW() WHERE user_id = ?");

        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $db->error);
        }

        // Bind the email parameter (string type)
        $stmt->bind_param("s", $email);

        // Execute the query and return the result
        if ($stmt->execute()) {
            return true;
        } else {
            return throw new Exception("Failed to execute query: " . $stmt->error);
        }

    }

    // Check if user is blocked
    public static function isBlocked($email): bool
    {
        global $db;
        $stmt = $db->prepare("SELECT attempt_time FROM login_attempts WHERE user_id = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $failedAttempts = $user['failed_attempts'];
            $lastFailedAttempt = strtotime($user['last_failed_attempt']);

            // Block if there are 7 failed attempts and less than 30 minutes have passed
            if ($failedAttempts >= 7 && (time() - $lastFailedAttempt) < 1800) {
                return true;
            }
        }
        return false; // User is not blocked
    }
}

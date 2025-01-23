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
        if (!$dbConnection) {
            throw new \Exception("Database connection is not valid.");
        }
        $this->dbConnection = $dbConnection;
    }

    // Registers the user in the database
    public function registerUser($email, $full_name, $username, $password, $verificationCode)
    {
        // Check if the email already exists
        if ($this->isEmailTaken($email)) {
            return false;
        }

        //$verificationCode = $this->generateVerificationCode();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert user data into the database
        $stmt = $this->dbConnection->prepare("INSERT INTO users (email, full_name,username, password, verification_code, is_verified) VALUES (?, ?,?, ?, ?, 0)");
        $stmt->bind_param("sssss", $email, $full_name, $username, $hashedPassword, $verificationCode);

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
    public static function getUserIdByEmail($email) {
        global $conn;
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user ? $user['id'] : null;
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
        error_log("emaili duke u loguar:" . $userId);
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
        $stmt = $this->dbConnection->prepare("UPDATE password_resets SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        return $stmt->execute();
    }
    public function insertResetToken($email, $token, $expiry)
    {
        $stmt = $this->dbConnection->prepare("INSERT INTO password_resets (email, reset_token, reset_token_expiry) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->dbConnection->error);
        }

        $stmt->bind_param("sss", $email, $token, $expiry);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $stmt->close();
        return true;
    }



    public function verifyResetToken($token): bool
    {
        $stmt = $this->dbConnection->prepare("SELECT * FROM password_resets where reset_token =? ");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function updatePassword($token, $email, $hashedPassword)
    {
        $passUpdateStmt = $this->dbConnection->prepare("UPDATE users SET password = ? WHERE email = ?");
        $passUpdateStmt->bind_param("ss", $hashedPassword, $email);
        if($passUpdateStmt->execute())
            return true;
        $passUpdateStmt->close();
        $stmt = $this->dbConnection->prepare("UPDATE password_resets SET reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
        $stmt->bind_param("ss", $hashedPassword, $token);
        return $stmt->execute();
    }

    // Get failed attempts
    public function getFailedAttempts($email): int
    {
        $stmt = $this->dbConnection->prepare("SELECT COUNT(*) FROM login_attempts WHERE user_id = (SELECT id FROM users WHERE email = ?) AND last_failed_attempt >= NOW() - INTERVAL 30 MINUTE");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0];
    }


    // Reset failed login attempts

    public function resetFailedAttempts($email): bool {
        error_log("Resetting failed attempts for: $email");
        $stmt = $this->dbConnection->prepare(
            "UPDATE login_attempts SET failed_attempts = 0 WHERE user_id = (SELECT id FROM users WHERE email = ?)"
        );
        $stmt->bind_param("s", $email);
        $result = $stmt->execute();
        if (!$result) {
            error_log("Failed to reset failed attempts: " . $this->dbConnection->error);
        }
        return $result;
    }

    public function incrementFailedAttempts($email): bool {
        error_log("Incrementing failed attempts for: $email");

        $stmt = $this->dbConnection->prepare(
            "INSERT INTO login_attempts (user_id, failed_attempts, last_failed_attempt)
        VALUES ((SELECT id FROM users WHERE email = ?), 1, NOW())
        ON DUPLICATE KEY UPDATE failed_attempts = failed_attempts + 1, last_failed_attempt = NOW()"
        );

        $stmt->bind_param("s", $email);

        $result = $stmt->execute();
        if (!$result) {
            error_log("Failed to update failed attempts: " . $this->dbConnection->error);
        }

        return $result;
    }

    public function isBlocked($email): bool
    {
        // Merrni ID-në e përdoruesit nga emaili
        $stmt = $this->dbConnection->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Ekzekuto pyetjen për tentativat e login-it
            $stmt = $this->dbConnection->prepare(
                "SELECT COUNT(*) as failed_attempts, MAX(last_failed_attempt) as last_failed_attempt
             FROM login_attempts
             WHERE user_id = ? AND last_failed_attempt >= NOW() - INTERVAL 2 MINUTE"
            );
            $stmt->bind_param("i", $user['id']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                $failedAttempts = $data['failed_attempts'];
                $lastFailedAttempt = strtotime($data['last_failed_attempt']);
                $isBlocked = $failedAttempts >= 7 && (time() - $lastFailedAttempt) < 1800;

                error_log("Failed Attempts: $failedAttempts");
                error_log("Last Failed Attempt: " . date('Y-m-d H:i:s', $lastFailedAttempt));
                error_log("Is Blocked: " . ($isBlocked ? 'Yes' : 'No'));

                return $isBlocked;
            }
        }

        return false;
    }

    public function searchByName($name)
    {
        $stmt = $this->dbConnection->prepare("SELECT * FROM users WHERE full_name LIKE ?");
        $searchTerm = "%" . $name . "%"; // Add wildcards for partial matching
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch and return results
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    public function likeUser($userId, $likedUserId)
    {
        // Insert a like into the 'likes' table
        $stmt = $this->dbConnection->prepare("INSERT IGNORE INTO likes (user_id, liked_user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $likedUserId);
        $stmt->execute();

        // Check if the liked user also liked this user (to form a match)
        $stmt = $this->dbConnection->prepare("SELECT * FROM likes WHERE user_id = ? AND liked_user_id = ?");
        $stmt->bind_param("ii", $likedUserId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Insert into matches table if both users like each other
            $stmt = $this->dbConnection->prepare("INSERT INTO matches (user1_id, user2_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $userId, $likedUserId);
            $stmt->execute();
            return true; // It's a match!
        }

        return false; // Not a match yet
    }

    public function getMatches($userId)
    {
        $stmt = $this->dbConnection->prepare("
        SELECT u.* 
        FROM users u
        JOIN matches m ON (m.user1_id = u.id OR m.user2_id = u.id)
        WHERE (m.user1_id = ? OR m.user2_id = ?) AND u.id != ?");
        $stmt->bind_param("iii", $userId, $userId, $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getNotifications($userId)
    {
        error_log("Fetching notifications for user ID: $userId"); // Add log to verify the user ID
        $query = "SELECT id, type, message, is_read, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->dbConnection->prepare($query);

        if (!$stmt) {
            throw new Exception("Database error: " . $this->dbConnection->error);
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $notifications = $result->fetch_all(MYSQLI_ASSOC);

        if (empty($notifications)) {
            error_log("No notifications found for user ID: $userId"); // Log if no notifications are found
        }

        return $notifications;
    }


    public function getUserByResetToken($token)
    {
        $stmt = $this->dbConnection->prepare("SELECT users.id, users.email FROM users JOIN password_resets ON users.id = password_resets.user_id WHERE password_resets.reset_token = ?");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }
        public function updateUserPassword($userId, $newPassword): bool
        {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->dbConnection->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param('si', $hashedPassword, $userId);
        $stmt->execute(); $stmt->close();
        return $stmt->affected_rows > 0;
    }
    public function deleteResetToken($token): bool
    {
        $stmt = $this->dbConnection->prepare("DELETE FROM password_resets WHERE reset_token = ?");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $stmt->close();
        return $stmt->affected_rows > 0;
    }
    public function getUserCount() {
        $sql = "SELECT COUNT(*) as count FROM users";
        $result = $this->dbConnection->query($sql);
        return $result->fetch_assoc()['count'];
    }

    public function getLikesCount() {
        $sql = "SELECT COUNT(*) as count FROM likes";
        $result = $this->dbConnection->query($sql);
        return $result->fetch_assoc()['count'];
    }


    public function getMatchesCount() {
        $sql = "SELECT COUNT(*) as count FROM matches";
        $result = $this->dbConnection->query($sql);
        return $result->fetch_assoc()['count'];
    }

    public function getAllUsers() {
        $sql = "SELECT users.full_name, users.email, profile.created_at 
                FROM users 
                JOIN profile ON users.id = profile.user_id";
        $result = $this->dbConnection->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }




}
<?php

class UserModel
{
    private $dbConnection;

    public function __construct($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    // Registers the user in the database
    public function registerUser($email, $password)
    {
        // Check if the email already exists
        if ($this->isEmailTaken($email)) {
            return false;
        }

        $verificationCode = $this->generateVerificationCode();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert user data into the database
        $stmt = $this->dbConnection->prepare("INSERT INTO users (email, password, verification_code, is_verified) VALUES (?, ?, ?, 0)");
        $stmt->bind_param("sss", $email, $hashedPassword, $verificationCode);

        return $stmt->execute();
    }

    // Authenticates the user by checking email and password
    public function authenticateUser($email, $password)
    {
        $stmt = $this->dbConnection->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
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
        $stmt->bind_param("i", $userId);
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
    public function isUserVerified($email): bool
    {
        $stmt = $this->dbConnection->prepare("SELECT is_verified FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
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
        $stmt = $this->dbConnection->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        return $stmt->execute();
    }

    public function verifyResetToken($token): bool
    {
        $stmt = $this->dbConnection->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function updatePassword($token, $hashedPassword)
    {
        $stmt = $this->dbConnection->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
        $stmt->bind_param("ss", $hashedPassword, $token);
        return $stmt->execute();
    }

    // Reset failed login attempts
    public static function resetFailedAttempts($email): bool
    {
        global $db;
        $stmt = $db->prepare("UPDATE users SET failed_attempts = 0 WHERE email = ?");
        $stmt->bind_param("s", $email);
        return $stmt->execute();
    }

    // Save Remember Me Token
    public static function saveRememberMeToken($userId, $token): bool
    {
        global $db;
        $stmt = $db->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
        $stmt->bind_param("si", $token, $userId);
        return $stmt->execute();
    }

    // Increment failed login attempts
    public static function incrementFailedAttempts($email): bool
    {
        global $db;
        $stmt = $db->prepare("UPDATE users SET failed_attempts = failed_attempts + 1, last_failed_attempt = NOW() WHERE email = ?");
        $stmt->bind_param("s", $email);
        return $stmt->execute();
    }

    // Check if user is blocked
    public static function isBlocked($email): bool
    {
        global $db;
        $stmt = $db->prepare("SELECT failed_attempts, last_failed_attempt FROM users WHERE email = ?");
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


    /**
     * Update the user's profile information.
     *
     * @param int $userId - The user's ID
     * @param string $name - The user's updated name
     * @param string $email - The user's updated email
     * @return bool - Returns true on success, false on failure
     */
    /**
     * Update the user's profile information.
     *
     * @param int $userId - The user's ID
     * @param array $data - The updated profile data
     * @return bool - Returns true on success, false on failure
     */
    public function updateUserProfile($userId, $data): bool
    {
        // Sanitize inputs
        $fullName = htmlspecialchars($data['full_name'], ENT_QUOTES, 'UTF-8');
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $profilePicture = $data['profile_picture'] ?? 'default.png'; // Default profile picture if not provided
        $age = filter_var($data['age'], FILTER_SANITIZE_NUMBER_INT);
        $gender = $data['gender'];
        $location = htmlspecialchars($data['location'], ENT_QUOTES, 'UTF-8');
        $selfSummary = htmlspecialchars($data['self_summary'], ENT_QUOTES, 'UTF-8');
        $hobby = htmlspecialchars($data['hobby'], ENT_QUOTES, 'UTF-8');
        $doingWithLife = htmlspecialchars($data['doing_with_life'], ENT_QUOTES, 'UTF-8');
        $goodAt = htmlspecialchars($data['good_at'], ENT_QUOTES, 'UTF-8');
        $ethnicity = htmlspecialchars($data['ethnicity'], ENT_QUOTES, 'UTF-8');
        $height = filter_var($data['height'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        // Prepare the SQL query to update user profile
        $query = "UPDATE profile 
                  SET full_name = :full_name, email = :email, profile_picture = :profile_picture, 
                      age = :age, gender = :gender, location = :location, self_summary = :self_summary, 
                      hobby = :hobby, doing_with_life = :doing_with_life, good_at = :good_at, 
                      ethnicity = :ethnicity, height = :height, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = :userId";

        try {
            // Prepare the SQL statement
            $stmt = $this->dbConnection->prepare($query);

            // Bind the parameters to the SQL query
            $stmt->bindParam(':full_name', $fullName, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':profile_picture', $profilePicture, PDO::PARAM_STR);
            $stmt->bindParam(':age', $age, PDO::PARAM_INT);
            $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
            $stmt->bindParam(':location', $location, PDO::PARAM_STR);
            $stmt->bindParam(':self_summary', $selfSummary, PDO::PARAM_STR);
            $stmt->bindParam(':hobby', $hobby, PDO::PARAM_STR);
            $stmt->bindParam(':doing_with_life', $doingWithLife, PDO::PARAM_STR);
            $stmt->bindParam(':good_at', $goodAt, PDO::PARAM_STR);
            $stmt->bindParam(':ethnicity', $ethnicity, PDO::PARAM_STR);
            $stmt->bindParam(':height', $height, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            // Check if any rows were affected (meaning the update was successful)
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                // No rows affected, maybe the same data was submitted
                return false;
            }
        } catch (PDOException $e) {
            // Log the error (optional)
            error_log("Error updating profile: " . $e->getMessage());
            return false;
        }
    }
    public function getUserProfile($userId): ?array {
        $query = "SELECT * FROM profile WHERE id = :userId";

        try {
            // Prepare the SQL statement
            $stmt = $this->dbConnection->prepare($query);

            // Bind the user ID parameter
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            // Fetch the user profile data
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            // Return the user data if found, otherwise return null
            return $userData ? $userData : null;
        } catch (PDOException $e) {
            // Log the error (optional)
            error_log("Error fetching user profile: " . $e->getMessage());
            return null;
        }
    }
}

}
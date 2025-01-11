<?php
namespace App\models;

class Profile {
    private  $dbConnection;

    // Constructor to initialize the database connection
    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    /**
     * Retrieve profile data for a user.
     *
     * @param int $userId - The user's ID
     * @return array|null - Returns the profile data or null if not found
     */
    public function getProfileData(int $userId): ?array {
        $query = "SELECT * FROM profile WHERE id = ?";

        if ($stmt = $this->dbConnection->prepare($query)) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                return null;
            }
        } else {
            error_log("Error preparing query: " . $this->dbConnection->error);
            return null;
        }
    }

    /**
     * Update the profile information of a user.
     *
     * @param int $userId - The user's ID
     * @param array $data - The new profile data
     * @return bool - Returns true if the update was successful, false otherwise
     */
    public function updateProfile(int $userId, array $data): bool {
        $query = "UPDATE profile 
                  SET full_name = ?, email = ?, age = ?, gender = ?, location = ?, 
                      self_summary = ?, hobby = ?, doing_with_life = ?, good_at = ?, 
                      ethnicity = ?, height = ? 
                  WHERE id = ?";

        if ($stmt = $this->dbConnection->prepare($query)) {
            // Bind parameters
            $stmt->bind_param(
                "ssissssssssi",
                $data['full_name'],
                $data['email'],
                $data['age'],
                $data['gender'],
                $data['location'],
                $data['self_summary'],
                $data['hobby'],
                $data['doing_with_life'],
                $data['good_at'],
                $data['ethnicity'],
                $data['height'],
                $userId
            );

            // Execute the statement and return the result
            return $stmt->execute();
        } else {
            error_log("Error preparing update query: " . $this->dbConnection->error);
            return false;
        }
    }

    /**
     * Get user profile by user ID.
     *
     * @param int $userId - The user's ID
     * @return array|null - Returns the user profile data or null if not found
     */
    public function getUserProfile(int $userId): ?array {
        $query = "SELECT * FROM profile WHERE id = ?";

        if ($stmt = $this->dbConnection->prepare($query)) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                return null;
            }
        } else {
            error_log("Error preparing query: " . $this->dbConnection->error);
            return null;
        }
    }

    /**
     * Update user profile information.
     *
     * @param int $userId - The user's ID
     * @param array $data - The new profile data
     * @return bool - Returns true if update is successful, false otherwise
     */
    public function updateUserProfile(int $userId, array $data): bool {
        // Sanitize inputs
        $fullName = htmlspecialchars($data['full_name'], ENT_QUOTES, 'UTF-8');
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $age = filter_var($data['age'], FILTER_SANITIZE_NUMBER_INT);
        $gender = $data['gender'];
        $location = htmlspecialchars($data['location'], ENT_QUOTES, 'UTF-8');
        $selfSummary = htmlspecialchars($data['self_summary'], ENT_QUOTES, 'UTF-8');
        $hobby = htmlspecialchars($data['hobby'], ENT_QUOTES, 'UTF-8');
        $doingWithLife = htmlspecialchars($data['doing_with_life'], ENT_QUOTES, 'UTF-8');
        $goodAt = htmlspecialchars($data['good_at'], ENT_QUOTES, 'UTF-8');
        $ethnicity = htmlspecialchars($data['ethnicity'], ENT_QUOTES, 'UTF-8');
        $height = filter_var($data['height'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        // Prepare the SQL query to update the user profile
        $query = "UPDATE profile 
                  SET full_name = ?, email = ?, age = ?, gender = ?, location = ?, 
                      self_summary = ?, hobby = ?, doing_with_life = ?, good_at = ?, 
                      ethnicity = ?, height = ?, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = ?";

        if ($stmt = $this->dbConnection->prepare($query)) {
            $stmt->bind_param(
                "ssissssssssi",
                $fullName,
                $email,
                $age,
                $gender,
                $location,
                $selfSummary,
                $hobby,
                $doingWithLife,
                $goodAt,
                $ethnicity,
                $height,
                $userId
            );

            return $stmt->execute();
        } else {
            error_log("Error preparing user profile update query: " . $this->dbConnection->error);
            return false;
        }
    }
}

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
        $query = "SELECT * FROM profile WHERE user_id = ?";

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
                  SET age = ?, gender = ?, location = ?, 
                      self_summary = ?, hobby = ?, doing_with_life = ?, good_at = ?, 
                      ethnicity = ?, height = ? 
                  WHERE profile.user_id = ?";

        if ($stmt = $this->dbConnection->prepare($query)) {
            // Bind parameters
            $stmt->bind_param(
                "ssissssssssd",
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
        $query = "SELECT * FROM profile WHERE $userId = ?";

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
        $sql = "UPDATE profile 
            SET profile_picture = ?, age = ?, gender = ?, location = ?, self_summary = ?, hobby = ?, doing_with_life = ?, good_at = ?, ethnicity = ?, height = ?
            WHERE user_id = ?";

        // Prepare statement
        $stmt = $this->dbConnection->prepare($sql);

        // Bind parameters
        $stmt->bind_param(
            "sissssssssi", // Define parameter types: s = string, i = integer
            $data['profile_picture'],
            $data['age'],
            $data['gender'],
            $data['location'],
            $data['self_summary'],
            $data['hobby'],
            $data['doing_with_life'],
            $data['good_at'],
            $data['ethnicity'],
            $data['height'],
            $userId // The user_id parameter
        );

        // Execute the query and check for success
        return $stmt->execute();
    }
    function createProfile($user_id, $profile_picture, $age, $gender, $location, $self_summary, $hobby, $doing_with_life, $good_at, $ethnicity, $height) {

        // Prepare the SQL statement
        $sql = "INSERT INTO profile (user_id, profile_picture, age, gender, location, self_summary, hobby, doing_with_life, good_at, ethnicity, height) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $this->dbConnection->prepare($sql);

        if (!$stmt) {
            die("Statement preparation failed: " . $this->dbConnection->error);
        }

        // Bind parameters
        $stmt->bind_param("isisssssssd", $user_id, $profile_picture, $age, $gender, $location, $self_summary, $hobby, $doing_with_life, $good_at, $ethnicity, $height);

        // Execute the statement
        return $stmt->execute();
    }
}

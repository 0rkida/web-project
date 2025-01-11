<?php
namespace App\models;
use PDO;
use PDOException;

class Profile {
private PDO  $dbConnection;

// Constructor to initialize database connection
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
$query = "SELECT * FROM profile WHERE id = :userId";

try {
$stmt = $this->dbConnection->prepare($query);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();
return $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
error_log("Error fetching profile data: " . $e->getMessage());
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
public function getupdateProfileData(int $userId, array $data): bool {
$query = "UPDATE profile SET
full_name = :full_name,
email = :email,
age = :age,
gender = :gender,
location = :location,
self_summary = :self_summary,
hobby = :hobby,
doing_with_life = :doing_with_life,
good_at = :good_at,
ethnicity = :ethnicity,
height = :height
WHERE id = :userId";

try {
$stmt = $this->dbConnection->prepare($query);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->bindParam(':full_name', $data['full_name']);
$stmt->bindParam(':email', $data['email']);
$stmt->bindParam(':age', $data['age'], PDO::PARAM_INT);
$stmt->bindParam(':gender', $data['gender']);
$stmt->bindParam(':location', $data['location']);
$stmt->bindParam(':self_summary', $data['self_summary']);
$stmt->bindParam(':hobby', $data['hobby']);
$stmt->bindParam(':doing_with_life', $data['doing_with_life']);
$stmt->bindParam(':good_at', $data['good_at']);
$stmt->bindParam(':ethnicity', $data['ethnicity']);
$stmt->bindParam(':height', $data['height']);

$stmt->execute();
return true;
} catch (PDOException $e) {
error_log("Error updating profile data: " . $e->getMessage());
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
            return $userData ?: null;
        } catch (PDOException $e) {
            // Log the error (optional)
            error_log("Error fetching user profile: " . $e->getMessage());
            return null;
        }
    }
    public function getupdateUserProfile($userId, $data): bool
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
            $stmt->bindParam(':full_name', $fullName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':profile_picture', $profilePicture);
            $stmt->bindParam(':age', $age, PDO::PARAM_INT);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':self_summary', $selfSummary);
            $stmt->bindParam(':hobby', $hobby);
            $stmt->bindParam(':doing_with_life', $doingWithLife);
            $stmt->bindParam(':good_at', $goodAt);
            $stmt->bindParam(':ethnicity', $ethnicity);
            $stmt->bindParam(':height', $height);
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


}


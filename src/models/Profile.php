<?php
class ProfileModel {
private $dbConnection;

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
public function getProfileData($userId): ?array {
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
public function updateProfileData($userId, array $data): bool {
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
$stmt->bindParam(':full_name', $data['full_name'], PDO::PARAM_STR);
$stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
$stmt->bindParam(':age', $data['age'], PDO::PARAM_INT);
$stmt->bindParam(':gender', $data['gender'], PDO::PARAM_STR);
$stmt->bindParam(':location', $data['location'], PDO::PARAM_STR);
$stmt->bindParam(':self_summary', $data['self_summary'], PDO::PARAM_STR);
$stmt->bindParam(':hobby', $data['hobby'], PDO::PARAM_STR);
$stmt->bindParam(':doing_with_life', $data['doing_with_life'], PDO::PARAM_STR);
$stmt->bindParam(':good_at', $data['good_at'], PDO::PARAM_STR);
$stmt->bindParam(':ethnicity', $data['ethnicity'], PDO::PARAM_STR);
$stmt->bindParam(':height', $data['height'], PDO::PARAM_STR);

$stmt->execute();
return true;
} catch (PDOException $e) {
error_log("Error updating profile data: " . $e->getMessage());
return false;
}
}
}


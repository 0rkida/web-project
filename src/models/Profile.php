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

return $result->num_rows > 0 ? $result->fetch_assoc() : null;
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
public function updateUserProfile(int $userId, array $data): bool {
$sql = "UPDATE profile
SET  age = ?, gender = ?, location = ?, self_summary = ?, hobby = ?, doing_with_life = ?, good_at = ?, ethnicity = ?, height = ?
WHERE user_id = ?";

if ($stmt = $this->dbConnection->prepare($sql)) {
$stmt->bind_param(
"ssssssssii",

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
return $stmt->execute();
} else {
error_log("Error preparing update query: " . $this->dbConnection->error);
return false;
}
}

/**
* Create a profile for a user.
*
* @param int $user_id - The user's ID
* @param int $age - The user's age
* @param string $gender - The user's gender
* @param string $location - The user's location
* @param string $self_summary - The user's self-summary
* @param string $hobby - The user's hobby
* @param string $doing_with_life - The user's life focus
* @param string $good_at - The user's strengths
* @param string $ethnicity - The user's ethnicity
* @param float $height - The user's height
* @return bool - Returns true if the profile was created successfully, false otherwise
*/
public function createProfile( int $age, string $gender, string $location, string $self_summary, string $hobby, string $doing_with_life, string $good_at, string $ethnicity, float $height): bool {
$sql = "INSERT INTO profile (age, gender, location, self_summary, hobby, doing_with_life, good_at, ethnicity, height)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

if ($stmt = $this->dbConnection->prepare($sql)) {
$stmt->bind_param(
"isssssssd",
 $age, $gender, $location, $self_summary, $hobby, $doing_with_life, $good_at, $ethnicity, $height);
return $stmt->execute();
} else {
error_log("Error preparing insert query: " . $this->dbConnection->error);
return false;
}
}
}

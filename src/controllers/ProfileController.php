<?php
session_start();
class ProfileController {
public ProfileModel $profileModel;

public function __construct($dbConnection) {
$this->profileModel = new ProfileModel($dbConnection);
}

public function getView(): void {
if (!isset($_SESSION['userId'])) {
header('Location: /login');
exit();
}

$userId = $_SESSION['userId'];
$userProfile = $this->profileModel->getProfileData($userId);

if ($userProfile) {
require "path/to/profile/view.php";
} else {
echo "Profile not found.";
}
}

public function postProfile($data): void {
if (!isset($_SESSION['userId'])) {
header('Location: /login');
exit();
}

$userId = $_SESSION['userId'];
$updated = $this->profileModel->updateProfileData($userId, $data);

if ($updated) {
echo "Profile updated successfully!";
header('Location: /profile');
} else {
echo "Error updating profile.";
}
}
}

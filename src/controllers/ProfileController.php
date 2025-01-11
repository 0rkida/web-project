<?php
namespace App\controllers;
use AllowDynamicProperties;
use App\models\Profile;

session_start();
#[AllowDynamicProperties] class ProfileController {
public Profile $profile;

public function __construct($dbConnection) {
$this->profile = new Profile($dbConnection);
}

public function getView(): void {
if (!isset($_SESSION['userId'])) {
header('Location: /login');
exit();
}

$userId = $_SESSION['userId'];
$userProfile = $this->profile->getProfileData($userId);
$userProfile = $this->profile->getUserProfile($userId);
$userProfile = $this->profile->getupdateProfileData($userId, $userProfile);
$userProfile = $this->profile->getupdateUserProfile($userId, $userProfile);

if ($userProfile) {
require "C:\xampp\htdocs\web-project\public\profile";
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
$this->profile->getupdateProfileData($userId, $data);
$updated = $this->profile->getupdateUserProfile($userId, $data);


if ($updated) {
echo "Profile updated successfully!";
header('Location: /profile');
} else {
echo "Error updating profile.";
}
}

}

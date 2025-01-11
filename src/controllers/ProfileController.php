<?php
namespace App\controllers;
use AllowDynamicProperties;
use App\models\Profile;

session_start();

#[AllowDynamicProperties]
class ProfileController {
    public Profile $profile;

    public function __construct($dbConnection) {
        $this->profile = new Profile($dbConnection);
    }

    // Get view for profile page
    public function getView(): void {
        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['userId'];

        // Fetch the user profile data from the database
        $userProfile = $this->profile->getProfileData($userId);

        // If the profile data is successfully fetched, pass it to the view
        if ($userProfile) {
            include 'src/views/profile_view.php';  // Pass the profile to the view
        } else {
            echo "Profile not found.";
        }
    }



    // Handle the profile update on POST request
    public function postProfile($data): void {
        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['userId'];

        // Update the profile data
        $updated = $this->profile->updateProfile($userId, $data);

        if ($updated) {
            echo "profile updated successfully!";
            header('Location: /profile');
        } else {
            echo "Error updating profile.";
        }
    }
}

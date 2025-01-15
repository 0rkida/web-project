<?php
namespace App\controllers;
use AllowDynamicProperties;
require_once __DIR__.'/../models/Profile.php';
require_once __DIR__.'/../models/User.php';
use App\models\Profile;
use App\models\User;

//session_start();

#[AllowDynamicProperties]
class ProfileController {
    public Profile $profile;
    public User $user;


    public function __construct($dbConnection) {
        $this->profile = new Profile($dbConnection);
        $this->user= new User($dbConnection);
    }

    // Get view for profile page
    public function getView(): void {
//        session_start();
        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['userId'];

        // Fetch the user profile data from the database
      $user=$this->user->getUserById($userId);
        $userProfile = $this->profile->getProfileData($userId);



        // If the profile data is successfully fetched, pass it to the view
        if ($userProfile) {
       $full_name = $user['full_name'];
 // Assuming $user is an instance of the user model

            $location = $userProfile['location'];
            $summary = $userProfile['self_summary'];
            $height = $userProfile['height'];
            include __DIR__.'/../views/profile.php';  // Pass the profile to the view
        } else {
            include __DIR__.'/../../public/profile/initialProfile.html';
//            echo "Profile not found.";
        }
    }

    public function getUpdateView(): void {
        $data = $this->profile->getProfileData($_SESSION['userId']);
        error_log("entered the function");
        include __DIR__.'/../views/editProfile.php';
    }
    public function postProfile(): void {
        if (!isset($_SESSION['userId'])){
            header('Location: /login');
            exit();
        }
        $result = $this->profile->createProfile(
            $_SESSION['userId'],
            $_POST['profile_picture'],
            $_POST['age'],
            $_POST['gender'],
            $_POST['location'],
            $_POST['self_summary'],
            $_POST['hobby'],
            $_POST['doing_with_life'],
            $_POST['good_at'],
            $_POST['ethnicity'],
            $_POST['height'],
        );
        if($result){
            header('Location: /profil');
        }
        else{
            echo "Something went wrong.";
        }

    }


    // Handle the profile update on POST request
    public function putProfile($data): void {
        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['userId'];

        // Update the profile data
        $updated = $this->profile->updateUserProfile($userId, $data);

        if ($updated) {
            echo "profile updated successfully!";
            header('Location: /profil/update');
        } else {
            echo "Error updating profile.";
        }
    }
}

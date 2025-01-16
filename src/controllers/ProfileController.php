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
        $this->checkSessionTimeout(); // Check session timeout on every instantiation
        $this->profile = new Profile($dbConnection);
        $this->user = new User($dbConnection);
    }

    public function checkSessionTimeout(): void {
        session_start();
        $timeout = 900; // 15 minutes

        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout)) {
            session_unset();
            session_destroy();
            header("Location: /logout");
            exit();
        }

        $_SESSION['LAST_ACTIVITY'] = time();
    }

    public function getView(): void {
        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['userId'];
        $user = $this->user->getUserById($userId);
        $userProfile = $this->profile->getProfileData($userId);

        if ($userProfile) {
            $full_name = $user['full_name'];
            $location = $userProfile['location'];
            $summary = $userProfile['self_summary'];
            $height = $userProfile['height'];
            include __DIR__ . '/../views/profile.php';
        } else {
            include __DIR__ . '/../../public/profile/initialProfile.html';
        }
    }

    public function getUpdateView(): void {
        $this->checkSessionTimeout();
        $data = $this->profile->getProfileData($_SESSION['userId']);
        include __DIR__ . '/../views/editProfile.php';
    }

    public function postProfile(): void {
        $this->checkSessionTimeout();

        if (!isset($_SESSION['userId'])) {
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

        if ($result) {
            header('Location: /profil');
        } else {
            echo "Something went wrong.";
        }
    }

    public function putProfile($data): void {
        $this->checkSessionTimeout();

        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['userId'];
        $updated = $this->profile->updateUserProfile($userId, $data);

        if ($updated) {
            header('Location: /profil/update');
        } else {
            echo "Error updating profile.";
        }
    }
}

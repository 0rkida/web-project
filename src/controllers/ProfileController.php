<?php

namespace App\controllers;
use AllowDynamicProperties;
require_once __DIR__.'/../models/Profile.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/UserPhotos.php';

use App\models\Profile;
use App\models\User;
use App\models\UserPhotos;
use Exception;

//session_start();

#[AllowDynamicProperties]
class ProfileController
{
    public Profile $profile;

    public User $user;
    public UserPhotos $userPhotos;


    public function __construct($dbConnection)
    {
        $this->checkSessionTimeout(); // Check session timeout on every instantiation
        $this->profile = new Profile($dbConnection);
        $this->user = new User($dbConnection);

        $this->userPhotos = new UserPhotos($dbConnection);
    }

    public function checkSessionTimeout(): void
    {
        //   session_start();
        $timeout = 900; // 15 minutes

        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout)) {
            session_unset();
            session_destroy();
            header("Location: /logout");
            exit();
        }

        $_SESSION['LAST_ACTIVITY'] = time();
    }

    public function getView(): void
    {
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

    public function getUpdateView(): void
    {
        $this->checkSessionTimeout();
        $data = $this->profile->getProfileData($_SESSION['userId']);
        include __DIR__ . '/../views/editProfile.php';
        header('Location: /profil/update');
    }

    public function postProfile(): void
    {
        $this->checkSessionTimeout();

        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        }

        $result = $this->profile->createProfile(
            $_SESSION['userId'],
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

    public function putProfile(array $data): void
    {
        $this->checkSessionTimeout();
        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        }
        $userId = $_SESSION['userId'];
        // Add the profile data received in the POST request
        $updated = $this->profile->updateUserProfile($userId, $data);
        if ($updated) {
            header('Location: /profil');  // Redirect after successful update
        } else {
            echo "Error updating profile.";
        }
    }


//    public function uploadPicture(array $file): string
//    {
//        $uploadsDir = __DIR__ . '/../../public/assets/img/user-uploads/albums/';
//        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
//
//        // Ensure the uploads directory exists
//        if (!is_dir($uploadsDir)) {
//            if (!mkdir($uploadsDir, 0755, true)) {
//                throw new Exception("Failed to create the upload directory.");
//            }
//        }
//
//        // Process the file
//        $fileName = $file['name'];
//        $fileTmp = $file['tmp_name'];
//        $fileSize = $file['size'];
//        $fileError = $file['error'];
//
//        if ($fileError === UPLOAD_ERR_OK) {
//            if ($fileSize < 5000000) { // 5MB limit
//                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
//
//                if (in_array($fileExtension, $allowedExtensions)) {
//                    $newFileName = uniqid('photo_') . '.' . $fileExtension;
//                    $filePath = $uploadsDir . $newFileName;
//
//                    // Move the file to the uploads directory
//                    if (move_uploaded_file($fileTmp, $filePath)) {
//                        return $filePath; // Return the file path
//                    } else {
//                        throw new Exception("Error moving the uploaded file.");
//                    }
//                } else {
//                    throw new Exception("Invalid file type: $fileName");
//                }
//            } else {
//                throw new Exception("$fileName exceeds the file size limit.");
//            }
//        } else {
//            throw new Exception("Error uploading $fileName.");
//        }
//    }
//
//
//
//    public function getPictures($userId): array
//    {
//        try {
//            return $this->userPhotos->getPhotosByUserId($userId);
//        } catch (Exception $e) {
//            error_log("Error fetching pictures for user ID $userId: " . $e->getMessage());
//            return [];
//        }
//    }

}

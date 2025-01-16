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

    public function putProfile($data): void
    {
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

    public function uploadPictures($userId, $files): array
    {
        $uploadsDir = 'public/assets/img/user-uploads/albums/';

// Check if the directory exists, if not, create it
        if (!file_exists($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);  // Create the directory with permissions
        }

        // Absolute path to the uploads directory
        $uploadsDir = __DIR__ . '/../../public/assets/img/user-uploads/albums/';
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $errors = [];
        $success = [];

        // Check if the directory exists, if not, create it
        if (!is_dir($uploadsDir)) {
            if (!mkdir($uploadsDir, 0777, true)) {
                $errors[] = "Failed to create the upload directory.";
                return ['success' => $success, 'errors' => $errors];
            }
        }

        // Process the uploaded files
        foreach ($files['tmp_name'] as $index => $tmpName) {
            $fileName = $files['name'][$index];
            $fileTmp = $files['tmp_name'][$index];
            $fileSize = $files['size'][$index];
            $fileError = $files['error'][$index];

            if ($fileError === UPLOAD_ERR_OK) {
                if ($fileSize < 5000000) { // 5MB limit
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    if (in_array($fileExtension, $allowedExtensions)) {
                        $newFileName = uniqid('photo_') . '.' . $fileExtension;
                        $filePath = $uploadsDir . $newFileName;

                        // Try moving the uploaded file
                        if (move_uploaded_file($fileTmp, $filePath)) {
                            try {
                                $this->userPhotos->savePicture($userId, $filePath);
                                $success[] = $fileName . " uploaded successfully.";
                            } catch (Exception $e) {
                                $errors[] = "Error saving $fileName: " . $e->getMessage();
                            }
                        } else {
                            $errors[] = "Error moving $fileName to the uploads directory.";
                        }
                    } else {
                        $errors[] = "$fileName has an invalid file type.";
                    }
                } else {
                    $errors[] = "$fileName exceeds the file size limit.";
                }
            } else {
                $errors[] = "Error uploading $fileName.";
            }
        }

        return ['success' => $success, 'errors' => $errors];
    }



    public function getPictures($userId): array
    {
        try {
            return $this->userPhotos->getPhotosByUserId($userId);
        } catch (Exception $e) {
            error_log("Error fetching pictures for user ID $userId: " . $e->getMessage());
            return [];
        }
    }

}

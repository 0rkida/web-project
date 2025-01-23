<?php
namespace App\controllers;

use AllowDynamicProperties;
use App\Models\Profile;
use App\Models\User;
use App\Models\Notification;
use App\Models\UserPhotos;
use Exception;

require_once __DIR__ . '/../models/Profile.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/UserPhotos.php';
require_once __DIR__ . '/../models/Notification.php';

#[AllowDynamicProperties]
class HomeController
{
    public Profile $profile;
    public User $user;
    public Notification $notification;
    public UserPhotos $userPhotos;
    private $dbConnection;

    public function __construct($dbConnection)
    {
        $this->profile = new Profile($dbConnection);
        $this->user = new User($dbConnection);
        $this->userPhotos = new UserPhotos($dbConnection);
        $this->notification = new Notification($dbConnection);
    }

    public function getAllUsersPictures()
    {
        $users = $this->userPhotos->getAllUsersPictures();

        // Remove duplicates
        $uniqueUsers = [];
        foreach ($users as $user) {
            $userId = $user['username'] . $user['picture_path'];
            $uniqueUsers[$userId] = $user;
        }

        if (empty($uniqueUsers)) {
            echo "No profiles found.";
        } else {
            // Pass unique users array to the view
            $users = array_values($uniqueUsers);
            error_log(json_encode($users));
            include __DIR__ . '/../views/home.php';
        }
    }

    public function getProfile($userId)
    {
        // Fetch user profile data
        $userProfile = $this->user->getUserById($userId); // Assuming you have a method to get user details by ID

        // Fetch notifications
        $notifications = $this->notification->getNotifications($userId);

        // Include profile view
        include __DIR__ . '/../views/profile.php';
    }

    public function likeUserPicture($likerId, $likedUserId, $picturePath)
    {
        // Add logic to save the "like" in the database if needed

        // Send a notification to the liked user
        $this->notification->addLikeNotification($likerId, $likedUserId, $picturePath);

        echo "Like registered and notification sent.";
    }
    public function getNotifications($userId)
    {
        $query = "SELECT id, type, message, is_read, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->dbConnection->prepare($query);

        if (!$stmt) {
            throw new Exception("Database error: " . $this->dbConnection->error);
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function postNotification(array $data)
    {
        if (empty($data['user_id']) || empty($data['message']) || empty($data['type'])) {
            echo "User ID, type, and message are required.";
            return;
        }

        $userId = $data['user_id'];
        $type = $data['type'];
        $message = $data['message'];

        $this->notification->addNotification($userId, $type, $message);

        echo "Notification sent successfully.";
    }
}

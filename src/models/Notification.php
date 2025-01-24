<?php
namespace App\Models;

use Exception;

class Notification
{
    private $dbConnection;

    public function __construct($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function addNotification($userId, $type, $message): bool
    {
        $query = "INSERT INTO notifications (user_id, type, message) VALUES (?, ?, ?)";
        $stmt = $this->dbConnection->prepare($query);

        if (!$stmt) {
            throw new Exception("Database error: " . $this->dbConnection->error);
        }

        $stmt->bind_param("iss", $userId, $type, $message);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Error adding notification: " . $stmt->error);
        }
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

    public function markNotificationAsRead($notificationId): void
    {
        $query = "UPDATE notifications SET is_read = TRUE WHERE id = ?";
        $stmt = $this->dbConnection->prepare($query);

        if (!$stmt) {
            throw new Exception("Database error: " . $this->dbConnection->error);
        }

        $stmt->bind_param("i", $notificationId);
        $stmt->execute();
    }

    // Handle like notification
    public function addLikeNotification($userId, $likedUserId, $picturePath): bool
    {
        // More descriptive message
        $message = "User with ID $userId liked your picture: $picturePath.";

        // Send notification to the liked user
        return $this->addNotification($likedUserId, 'like', $message);
    }
}

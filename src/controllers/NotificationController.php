<?php

namespace App\controllers;

use App\Models\Notification;
use mysqli;
use Exception;
require_once __DIR__ . '/../models/Notification.php';

class NotificationController
{
    private mysqli $conn;
    private Notification $notification;

    public function __construct(mysqli $dbConnection)
    {
        $this->conn = $dbConnection;
        $this->notification = new Notification($dbConnection);
    }

    // Get notifications for the logged-in user
    public function getView(): void
    {
        try {
            // Start session and check user authentication
            $this->startSession();
            $userId = $this->getUserIdFromSession();
            if (!$userId) {
                throw new Exception("User must be logged in.");
            }

            // Fetch notifications from the model
            $notifications = $this->notification->getNotifications($userId);

            // Log the notifications to check if they're being retrieved
            error_log(json_encode($notifications));

            // Mark notifications as read
            foreach ($notifications as $notification) {
                $this->notification->markNotificationAsRead($notification['id']);
            }

            // Render the notifications view
            include __DIR__ . '/../views/notifications.php';
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    // Send a new notification
    public function postNotification(array $data): void
    {
        try {
            // Validate input data
            $this->validateNotificationData($data);

            // Add notification using the model
            $this->notification->addNotification($data['user_id'], $data['type'], $data['message']);

            echo "Notification sent successfully.";
        } catch (Exception $e) {
            echo "Failed to send notification: " . $e->getMessage();
        }
    }

    // Start the session if not already started
    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Get user ID from the session
    private function getUserIdFromSession(): ?int
    {
        return $_SESSION['userId'] ?? null;
    }

    // Validate the data for a new notification
    private function validateNotificationData(array $data): void
    {
        if (empty($data['user_id']) || empty($data['message']) || empty($data['type'])) {
            throw new Exception("User ID, type, and message are required.");
        }
    }
}

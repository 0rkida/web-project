<?php


namespace App\controllers;

class NotificationController
{
    private \mysqli $conn;

    public function __construct(\mysqli $dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function getView(): void
    {
        // Fetch notifications for the logged-in user
        session_start();
        if (!isset($_SESSION['userId'])) {
            echo "You must be logged in to view notifications.";
            return;
        }

        $userId = $_SESSION['userId'];
        $stmt = $this->conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Render notifications view
        echo "<h2>Your Notifications</h2>";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p>{$row['message']} - <em>{$row['created_at']}</em></p>";
            }
        } else {
            echo "<p>No notifications found.</p>";
        }

        $stmt->close();
    }

    public function postNotification(array $data): void
    {
        if (empty($data['user_id']) || empty($data['message'])) {
            echo "User ID and message are required.";
            return;
        }

        $userId = $data['user_id'];
        $message = $data['message'];

        $stmt = $this->conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $userId, $message);

        if ($stmt->execute()) {
            echo "Notification sent successfully.";
        } else {
            echo "Failed to send notification.";
        }

        $stmt->close();
    }
}

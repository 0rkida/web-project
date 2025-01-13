<?php
namespace App\controllers;

global $dbConnection;
session_start();

use App\models\Notifications;
require_once "C:/xampp/htdocs/web-project/src/models/Notifications.php";

class NotificationController {
    private Notifications $notification;

    public function __construct($dbConnection) {
        $this->notification = new Notifications($dbConnection);
    }

    // Display the notification view
    public function getView(): void {
        require "C:/xampp/htdocs/web-project/public/notifications.html";
    }

    // Handle sending notifications based on type
    public function postNotification($data): void {
        header("Content-Type: application/json");

        if (isset($data['type'], $data['toUserId'], $data['fromUserName'])) {
            $type = $data['type'];
            $toUserId = intval($data['toUserId']);
            $fromUserName = htmlspecialchars(trim($data['fromUserName']));

            switch ($type) {
                case 'like':
                    $this->notification->sendLikeNotification($toUserId, $fromUserName);
                    echo json_encode(["success" => true, "message" => "Like notification sent successfully."]);
                    break;

                case 'match':
                    $this->notification->sendMatchNotification($toUserId, $fromUserName);
                    echo json_encode(["success" => true, "message" => "Match notification sent successfully."]);
                    break;

                case 'message':
                    $this->notification->sendMessageNotification($toUserId, $fromUserName);
                    echo json_encode(["success" => true, "message" => "Message notification sent successfully."]);
                    break;

                default:
                    echo json_encode(["error" => "Invalid notification type."]);
            }
        } else {
            echo json_encode(["error" => "Missing required fields."]);
        }
    }

    // Fetch notifications for a user
    public function getNotifications(): void {
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $this->notification->getNotifications($userId);
        } else {
            echo json_encode(["error" => "User not logged in."]);
        }
    }

    // Mark a specific notification as read
    public function markNotificationAsRead($notificationId): void {
        $this->notification->markNotificationAsRead($notificationId);
        echo json_encode(["success" => true, "message" => "Notification marked as read."]);
    }
}

// Request handling
$dbConnection = new $dbConnection(); // Assuming Db is defined in src/db.php
$conn = $dbConnection->getConnection();
$controller = new NotificationController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id'])) {
    $controller->getNotifications();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->postNotification($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['notification_id'])) {
    $notificationId = intval($_GET['notification_id']);
    $controller->markNotificationAsRead($notificationId);
} else {
    header("Content-Type: application/json");
    echo json_encode(["error" => "Invalid request."]);
}

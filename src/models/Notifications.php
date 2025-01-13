<?php
namespace App\models;

require_once './src/db.php';

class Notifications {
private $dbConnection;

// Constructor to initialize the database connection
public function __construct($dbConnection) {
$this->dbConnection = $dbConnection;
}

public function insertNotification($toUserId, $type, $message): void
{
$stmt = $this->dbConnection->prepare("INSERT INTO notifications (user_id, type, message) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $toUserId, $type, $message);
$stmt->execute();
}

public function sendLikeNotification($toUserId, $fromUserName): void
{
$message = "$fromUserName pëlqeu profilin tënd!";
$this->insertNotification($toUserId, 'like', $message);
}

public function sendMatchNotification($toUserId, $fromUserName): void
{
$message = "Urime! Ti dhe $fromUserName jeni bërë match!";
$this->insertNotification($toUserId, 'match', $message);
}

public function sendMessageNotification($toUserId, $fromUserName): void
{
$message = "$fromUserName të ka dërguar një mesazh!";
$this->insertNotification($toUserId, 'message', $message);
}

public function getNotifications($userId): void
{
header("Content-Type: application/json");

$stmt = $this->dbConnection->prepare(
"SELECT id, type, message, is_read, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC"
);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($notifications);
}

public function markNotificationAsRead($notificationId): void
{
$stmt = $this->dbConnection->prepare("UPDATE notifications SET is_read = TRUE WHERE id = ?");
$stmt->bind_param("i", $notificationId);
$stmt->execute();
}

public function deleteNotification($notificationId): void
{
// Optionally delete the notification from the database when the user dismisses it
$stmt = $this->dbConnection->prepare("DELETE FROM notifications WHERE id = ?");
$stmt->bind_param("i", $notificationId);
$stmt->execute();
}
}

// Handling user request for notifications
if (isset($_GET['user_id'])) {
$db = new db.php(); // Assuming Db is a class in src/db.php that provides a connection
$conn = $db->dbConnection(); // Get the database connection
$notification = new Notifications($conn);

$userId = intval($_GET['user_id']); // Make sure it's a number
$notification->getNotifications($userId);
} else {
header("Content-Type: application/json");
echo json_encode(["error" => "user_id është i detyrueshëm"]);
}

// Handling mark as read or delete notification
if (isset($_POST['notification_id'])) {
$notificationId = intval($_POST['notification_id']);
if (isset($_GET['mark_as_read'])) {
$notification->markNotificationAsRead($notificationId);
echo json_encode(["status" => "read"]);
} elseif (isset($_GET['delete'])) {
$notification->deleteNotification($notificationId);
echo json_encode(["status" => "deleted"]);
}
}

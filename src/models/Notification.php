<?php


global $conn;
function sendLikeNotification($toUserId, $fromUserName, $conn) {
    $message = "$fromUserName pëlqeu profilin tënd!";
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, type, message) VALUES (?, 'like', ?)");
    $stmt->bind_param("is", $toUserId, $message);
    $stmt->execute();
}

function sendMatchNotification($toUserId, $fromUserName, $conn) {
    $message = "Urime! Ti dhe $fromUserName jeni bërë match!";
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, type, message) VALUES (?, 'match', ?)");
    $stmt->bind_param("is", $toUserId, $message);
    $stmt->execute();
}

function sendMessageNotification($toUserId, $fromUserName, $conn) {
    $message = "$fromUserName të ka dërguar një mesazh!";
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, type, message) VALUES (?, 'message', ?)");
    $stmt->bind_param("is", $toUserId, $message);
    $stmt->execute();
}
require_once './src/db.php';

header("Content-Type: application/json");

function getNotifications($userId, $conn) {
    $stmt = $conn->prepare("SELECT id, type, message, is_read, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $notifications = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($notifications);
}

// Kontrollo nëse user_id është përcjellë në kërkesë
if (isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']); // Siguro që është një numër
    getNotifications($userId, $conn);
} else {
    echo json_encode(["error" => "user_id është i detyrueshëm"]);
}

function markNotificationAsRead($notificationId, $conn) {
    $stmt = $conn->prepare("UPDATE notifications SET is_read = TRUE WHERE id = ?");
    $stmt->bind_param("i", $notificationId);
    $stmt->execute();
}

function timeAgo($timestamp) {
    $timeAgo = strtotime($timestamp);
    $currentTime = time();
    $timeDifference = $currentTime - $timeAgo;

    if ($timeDifference < 60) {
        return "Just now";
    } elseif ($timeDifference < 3600) {
        $minutes = floor($timeDifference / 60);
        return "$minutes minute" . ($minutes > 1 ? "s" : "") . " ago";
    } elseif ($timeDifference < 86400) {
        $hours = floor($timeDifference / 3600);
        return "$hours hour" . ($hours > 1 ? "s" : "") . " ago";
    } else {
        $days = floor($timeDifference / 86400);
        return "$days day" . ($days > 1 ? "s" : "") . " ago";
    }
}






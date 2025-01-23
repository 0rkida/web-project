<?php
namespace App\controllers;

use App\models\Message;

session_start();
require_once "C:\xampp\htdocs\web-project\src\models\Message.php";

class MessageController {
    public message $message;
    public function __construct($dbConnection ) {

        $this->message= new message($dbConnection);
    }
    public function getView(): void {
        $viewPath = dirname(__DIR__, 2) . '/public/chat.html';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "View not found.";
        }
    }

    public function postMessage(array $data): void {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'User not authenticated.']);
            return;
        }

        $senderId = $_SESSION['user_id'];
        $receiverId = $data['receiver_id'] ?? null;
        $message = $data['message'] ?? '';

        if (empty($receiverId) || empty($message)) {
            echo json_encode(['error' => 'Receiver ID and message content are required.']);
            return;
        }

        $message = htmlspecialchars(trim($message));

        // Save the message in the database
        if ($this->message->saveMessage($senderId, $receiverId, $message)) {
            echo json_encode(['success' => true, 'message' => 'Message sent successfully.']);
        } else {
            echo json_encode(['error' => 'Failed to send the message.']);
        }
    }



    public function searchUsers(): void {
        header('Content-Type: application/json');

        if (!isset($_SESSION['unique_id'])) {
            echo json_encode(['error' => 'User not authenticated.']);
            return;
        }

        $outgoingId = $_SESSION['unique_id'];
        $searchTerm = $_POST['searchTerm'] ?? '';

        if (!empty($searchTerm)) {
            $users = $this->message->searchUsers($outgoingId, $searchTerm);

            if (!empty($users)) {
                echo json_encode(['success' => true, 'users' => $users]);
            } else {
                echo json_encode(['success' => false, 'error' => 'No users found.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Search term cannot be empty.']);
        }
    }


}

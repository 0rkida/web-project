<?php

session_start();
require_once "C:\xampp\htdocs\web-project\src\models\Message.php";

class MessageController {
    public messageModel $messageModel;
    public function __construct($dbConnection ) {

        $this->messageModel= new messageModel($dbConnection);
    }
    public function getView(): void {
        require "C:\xampp\htdocs\web-project\public\chat.html";
    }
    public function postMessage($data): void {
            // Check if message data is provided
            if (!empty($data['message'])) {
                // Clean and sanitize the input data
                $message = htmlspecialchars(trim($data['message']));
                $userId = $_SESSION['user_id'];  // Assuming the user is logged in and user_id is in session

                // Save the message to the database
                $this->messageModel->saveMessage($userId, $message);

                $messages = $this->messageModel->getMessages();
                foreach ($messages as $message) {
                    echo $message['username'] . ': ' . $message['message'] . '<br>';
                }


                // Optionally, return a response or redirect
                echo "Message sent successfully.";
            } else {
                echo "Message cannot be empty.";
            }
        }



}

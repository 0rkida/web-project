<?php
namespace App\controllers;
use  App\models\Message;

session_start();
require_once "C:\xampp\htdocs\web-project\src\models\Message.php";

class MessageController {
    public message $message;
    public function __construct($dbConnection ) {

        $this->message= new message($dbConnection);
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
                $this->message->saveMessage($userId, $message);

                $messages = $this->message->getMessages();
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

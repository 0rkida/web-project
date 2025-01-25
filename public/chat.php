<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="css/chat.css">
</head>
<body>
<header>
    <h1>Messages</h1>
</header>
<main>
    <section>
        <div id="container">
            <!-- Conversations -->
            <aside>
                <header>
                    <input type="text" id="search-input" placeholder="Search">
                </header>
                <ul id="user-list">
                    <!-- User profiles will be loaded here -->
                </ul>
            </aside>
            <!-- Start chatting with your matches. -->
            <main>
                <header id="chat-header">
                    <img src="" alt="Profile Picture" style="display: none;">
                    <div>
                        <h2>Select a user to chat</h2>
                        <h3></h3>
                    </div>
                    <div class="menu">
                        <button class="menu-button">•••</button>
                        <div class="menu-dropdown">
                            <a href="#">Block or Report</a>
                        </div>
                    </div>
                </header>
                <ul id="chat">
                    <!-- Chat messages will be loaded here -->
                </ul>
                <footer>
                    <textarea id="message-input" placeholder="Type your message"></textarea>
                    <a href="#" id="send-button">Send</a>
                </footer>
            </main>
        </div>
    </section>
</main>

<script src="js/chat.js"></script>
<?php
require_once "C:/xampp/htdocs/web-project/src/models/Message.php";
require_once "C:/xampp/htdocs/web-project/src/controllers/MessageController.php";

use App\controllers\MessageController;
use App\models\Message;

$dbConnection = new mysqli("localhost", "root", "", "datting_app");

$messageController = new MessageController($dbConnection);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $messageController->postMessage($data);
}
?>
</body>
</html>
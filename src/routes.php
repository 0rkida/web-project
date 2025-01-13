<?php

use App\Controllers\LogInController;
use App\Controllers\LogoutController;
use App\controllers\MatchController;
use App\controllers\MessageController;
use App\controllers\NotificationController;
use App\controllers\ProfileController;
use App\controllers\RegisterController;
use App\controllers\VerifyController;
use PHPMailer\PHPMailer\PHPMailer;

$uri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Lidhja me bazën e të dhënave
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datting_app";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Krijoni instancën e PHPMailer
require_once 'C:\xampp\htdocs\web-project\vendor\phpmailer\phpmailer\src\PHPMailer.php';
$mailer = new PHPMailer(true);

switch ($uri) {
    case '/':
        require 'C:\xampp\htdocs\web-project\public\home.html';
        break;

    case '/register':
        require 'controllers/registerController.php';
        $registerController = new RegisterController($conn, $mailer); // Kaloni lidhjen me DB dhe PHPMailer
        if ($requestMethod === 'GET') {
            $registerController->getView();
        } else if ($requestMethod === 'POST') {
            // Kaloni të dhënat e përdoruesit për regjistrim
            $registerController->postRegister([
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ]);
            // Redirect pas regjistrimit (mund të jetë një faqe tjetër)
            header('Location: /login'); // Mund të jetë një URL e ndryshme
            exit();
        }
        break;

    case '/LogIn':
        require 'controllers/LogInController.php';
        $LogInController = new LogInController($conn, $mailer); // Kaloni lidhjen me DB dhe PHPMailer
        if ($requestMethod === 'GET') {
            $LogInController->getView();
        } else if ($requestMethod === 'POST') {
            // Kaloni të dhënat e përdoruesit për login
            $LogInController->postLogin([
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ]);
            // Redirect pas login-it
            header('Location: /home'); // Mund të jetë një URL e ndryshme
            exit();
        }
        break;

        case '/Verify':
            require 'controllers/VerifyController.php';
            $VerifyController = new VerifyController($conn, $mailer);
            if ($requestMethod === 'GET') {
                $VerifyController->getView();

            } else if ($requestMethod === 'POST') {
                $VerifyController->postVerify([
                    'email' => $_POST['email'],
                    'password' => $_POST['password']

                ]);
                header('Location: /login');
                exit();
            }
            break;
    case '/Logout':
        // Include the controller class file
        require_once 'controllers/LogoutController.php';

        // Check if the controller class exists
        if (class_exists('\App\Controllers\LogoutController')) {
            // Create an instance of the LogOutController and call the logout method
            $logOutController = new LogoutController();
            $logOutController->logout();
        } else {
            // Handle error if the class doesn't exist
            echo "Error: LogOutController not found.";
        }
        break;

        case '/profileviews':
            require 'controllers/ProfileController.php';
            $ProfileController = new ProfileController($conn);
            if ($requestMethod === 'GET') {
                $ProfileController->getView();
            }else if ($requestMethod === 'POST') {
                $ProfileController->postProfile([
                    'firstName' => $_POST['firstName'],
                    'lastName' => $_POST['lastName'],
                    'email' => $_POST['email']
                    ]);
                header('Location: /profile');
                exit();
            }
            break;

    case '/Messages':
        require 'controllers/MessageController.php';
        $MessagesController = new MessageController($conn);
        if ($requestMethod === 'GET') {
            $MessagesController->getView();
        }else if ($requestMethod === 'POST') {
            $MessagesController->postMessage([
                'message' => $_POST['message'],
                'user_id' => $_SESSION['user_id']
            ]);
            header('Location: /chat');
            exit();
        }
        break;
    case '/Matches':
        require 'controllers/MatchController.php';
        $MatchController = new MatchController($conn);
        if ($requestMethod === 'GET') {
            $MatchController->getView();  // Display the match view
        } else if ($requestMethod === 'POST') {
            $MatchController->handlePostRequest();  // Handle the form submission (create, update, delete matches)
            header('Location: /chat');  // Redirect to another page after handling the post request
            exit();
        }
        break;

    case '/Notifications' :
        require 'controllers/NotificationController.php';
        $NotificationController = new NotificationController($conn);
        if($requestMethod === 'GET'){
            $NotificationController->getView();

        }else if ($requestMethod==='POST'){
            $NotificationController->postNotification([]);
            header('Location: /notification');
            exit();
        }
        break;

}

<?php
//session_set_cookie_params([
//   'lifetime' => 3600, // 1 hour
//    'path' => '/', // Available throughout the site
//    'domain' => 'localhost', // Ensure this matches your domain
//    'secure' => false, // Set to true if using HTTPS
//    'httponly' => true, // Make the cookie accessible only via HTTP and not JavaScript
//    'samesite' => 'Lax', // For cross-site request handling
//]);
//session_start();
require_once __DIR__.'/../vendor/autoload.php';

use App\Controllers\LogInController;
use App\controllers\MatchController;
use App\controllers\MessageController;
use App\controllers\NotificationController;
use App\controllers\ProfileController;
use App\controllers\RegisterController;
use App\controllers\VerifyController;
use PHPMailer\PHPMailer\PHPMailer;

//$uri = $_SERVER['REQUEST_URI'];
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

$request_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

error_log("$request_path");

switch (strtolower($request_path)) {
    case '/profil':
        require 'controllers/ProfileController.php';
        $ProfileController = new ProfileController($conn);
        if ($requestMethod === 'GET') {
//            echo 'correct url';
            $ProfileController->getView();
        }else if ($requestMethod === 'POST') {
            $ProfileController->postProfile();
//            header('Location: /profile');
            exit();
        }
        break;
    case '/':
        require '../public/MishEThu.html';
        break;

    case '/profil/update':
        require 'controllers/ProfileController.php';
        $ProfileController = new ProfileController($conn);
        if($requestMethod === 'GET'){
            $ProfileController->getUpdateView();
        }else if($requestMethod === 'POST'){
            $ProfileController->putProfile($_POST);
        }
        break;
    case '/home':
      //  error_log("After home redirect:". $_SESSION['userId']);
        require __DIR__.'/../public/home.html';
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
            echo 'all is good';
            // Redirect pas regjistrimit (mund të jetë një faqe tjetër)
//            header('Location: /login'); // Mund të jetë një URL e ndryshme
            exit();
        }
        break;

    case '/login':
        require 'controllers/LogInController.php';
        $LogInController = new LogInController($conn); // Kaloni lidhjen me DB dhe PHPMailer
        if ($requestMethod === 'GET') {
            $LogInController->getView();
        } else if ($requestMethod === 'POST') {
            // Kaloni të dhënat e përdoruesit për login
            $LogInController->postLogin([
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ]);
            // Redirect pas login-it
//            header('Location: /home'); // Mund të jetë një URL e ndryshme
//            exit();
        }
        break;
    case '/logout':
        require_once 'controllers/LogInController.php';
        $logInController = new LogInController($conn);
        $logInController->Logout();
        break;
    case '/verify':
            require 'controllers/VerifyController.php';
            $VerifyController = new VerifyController($conn, $mailer);
            if ($requestMethod === 'GET') {
                $VerifyController->getView();

            } else if ($requestMethod === 'POST') {
                $VerifyController->postVerify([
                    'email' => $_POST['email'],
                    'code' => $_POST['code']
                ]);
                header('Location: /login');
                exit();
            }
            break;
    // Ensures no further code is executed after logout

    case '/messages':
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
    case '/matches':
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


    case '/notifications' :
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

    default:
        http_response_code(404);
        echo "404 Not Found";
        break;

}

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

use App\controllers\AdminController;
use App\controllers\HomeController;
use App\Controllers\LogInController;
use App\controllers\MatchController;
use App\controllers\MessageController;
use App\controllers\NotificationController;
use App\controllers\ProfileController;
use App\controllers\RegisterController;
use App\controllers\SessionController;
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
require 'remember_me.php';

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
        require 'controllers/HomeController.php';
        $HomeController = new HomeController($conn);
      //  error_log("After home redirect:". $_SESSION['userId']);
        $HomeController->getAllUsersPictures();

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
        $LogInController = new LogInController($conn);

        if ($requestMethod === 'GET') {
            $LogInController->getView();
        } elseif ($requestMethod === 'POST') {
            $LogInController->handleLogin($_POST); // Delegate all POST logic to the controller
        }
        break;
    case '/forgot-password':
        require 'controllers/LogInController.php';
        $LogInController = new LogInController($conn);
        if ($requestMethod === 'GET') {
            $LogInController->getForgetPasswordView(); // Render the forgot password form
        } elseif ($requestMethod === 'POST') {
            $email = trim($_POST['email']);
            require_once __DIR__ . '/controllers/LoginController.php';
            $logInController = new LogInController($conn);
            $logInController->passwordReset($email);
            echo 'Email sent to: ' . $email;
            exit();
        }
        break;

    case '/reset-password':
        require 'controllers/LogInController.php';
        $LogInController = new LogInController($conn);
        if($requestMethod==='GET'){
            $LogInController->getResetPasswordView();
        }
        elseif ($requestMethod === 'POST') {
            $token = $_POST['token'];
            $email = $_POST['email'];
            $newPassword = $_POST['new_password'];
            $LogInController->resetPassword($token, $email, $newPassword); // Handle password reset
        }

        break;
        case '/check-session';
        require 'controllers/SessionController.php';
        $SessionController = new SessionController($conn);
        $SessionController->checkSession();
        break;


    case '/admin/dashboard':
                require 'controllers/AdminController.php';
                $AdminController = new AdminController($conn); // Kaloni lidhjen me DB
                if ($requestMethod === 'GET') {
                    $AdminController->getView(); // Show the admin dashboard
                }
                break;


    case '/logout':
        require_once 'controllers/LogInController.php';
        $logInController = new LogInController($conn);
        $logInController->Logout();
        setcookie("remember_me", "", [
            'path' => '/',
            'domain' => '', // Match the domain used in the cookie
            'secure' => true,
            'httponly' => true,
            'samesite' => 'String',
        ]);
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


    case '/notifications':
        require_once 'controllers/NotificationController.php'; // Make sure you are requiring NotificationController
        $notificationController = new NotificationController($conn);

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Call the getView method to display the notifications
            $notificationController->getView();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Call the postNotification method to send a notification
            $notificationController->postNotification($_POST);
            header('Location: /notifications');
            exit();
        }
        break;

    case '/like-picture':
        require 'controllers/HomeController.php';
        $HomeController = new HomeController($conn);
        if (isset($_POST['liker_id'], $_POST['liked_user_id'], $_POST['picture_path'])) {
            // Additional validation for IDs and path
            $likerId = filter_var($_POST['liker_id'], FILTER_VALIDATE_INT);
            $likedUserId = filter_var($_POST['liked_user_id'], FILTER_VALIDATE_INT);
            $picturePath = filter_var($_POST['picture_path'], FILTER_SANITIZE_STRING);

            if ($likerId && $likedUserId && !empty($picturePath)) {
                $HomeController->likeUserPicture($likerId, $likedUserId, $picturePath);
            } else {
                echo "Invalid input data.";
            }
        } else {
            echo "Invalid request.";
        }

    case '/search':
        require 'controllers/SearchController.php';
        $SearchController = new SearchController($conn);

        if ($requestMethod === 'GET') {
            $SearchController->getView();
        } else if ($requestMethod === 'POST') {
            $SearchController->postSearch($_POST);
        }
        break;

    case '/match/view':
        require 'controllers/MatchController.php';
        $matchController = new \App\controllers\MatchController($conn);

        if ($requestMethod === 'GET') {
            $type = $_GET['type'] ?? null; // 'matches' or 'notifications'
            $userId = $_SESSION['user_id'] ?? null; // Assuming user ID is stored in session

            if ($type && $userId) {
                $matchController->getView($type, $userId);
            } else {
                echo "Invalid request parameters.";
            }
        }
        break;

    case '/match/like':
        require 'controllers/MatchController.php';
        $matchController = new \App\controllers\MatchController($conn);

        if ($requestMethod === 'POST') {
            $likedByUserId = $_SESSION['user_id'] ?? null; // Assuming user ID is stored in session
            $likedUserId = $_POST['liked_user_id'] ?? null;

            if ($likedByUserId && $likedUserId) {
                $matchController->postLike($likedByUserId, $likedUserId);
            } else {
                echo "Invalid request parameters.";
            }
        }
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
        break;


}

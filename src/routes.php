<?php
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
}

<?php

namespace App\controllers;

use PHPMailer\PHPMailer\PHPMailer;
use App\models\User;
use JetBrains\PhpStorm\NoReturn;

require_once __DIR__.'/../models/User.php';

class LogInController
{
    public User $user;
    private PHPMailer $mailer;

    public function __construct($dbConnection)
    {
        $this->user = new User($dbConnection);
        $this->mailer = new PHPMailer(true);
    }

    public function getView(): void
    {
        if ($this->checkIfLoggedIn()) {
            header('Location: /home');
            exit();
        }
        require_once 'C:\xampp\htdocs\web-project\public\login.html';
    }

    public function postLogin(array $data): void
    {
      //  session_start(); // Ensure session is started

        // Trim and sanitize email and password inputs
        $email = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = trim($data['password'] ?? '');

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Email formati është i gabuar.";
            return;
        }

        // Check if email or password is empty
        if (empty($email) || empty($password)) {
            echo "Email dhe fjalëkalimi janë të detyrueshëm.";
            return;
        }

        // Debug: Log the email and password (password will be hashed, so won't expose sensitive data)
        error_log("Attempting login with Email: " . $email);

        // Authenticate user
        $userId = $this->user->authenticateUser($email, $password);
        error_log("User ID after authentication: " . ($userId ?  : "false"));

        if ($userId === false) {
            echo "Gabim! Email ose fjalëkalim i gabuar!";
        } else {
            // Check if the user is verified
            if (!$this->user->isUserVerified($userId)) {
                echo "Përdoruesi nuk është verifikuar ende. Kontrolloni email-in tuaj.";
                return;
            }

            // Regenerate session ID and store user info in session
            session_regenerate_id(true);
            $_SESSION['userId'] = $userId;
            error_log('User ID saved in session: ' . $_SESSION['userId']);
            $_SESSION['loggedIn'] = true;

            // Redirect to home page after successful login
            header('Location: /home');
        }
        exit();
    }

    public function checkIfLoggedIn(): bool
    {
        return isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
    }

    #[NoReturn] public function Logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: /login');
        exit();
    }
}

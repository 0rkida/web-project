<?php

namespace App\controllers;

use AllowDynamicProperties;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use App\models\User;
use JetBrains\PhpStorm\NoReturn;

require_once __DIR__ . '/../models/User.php';

#[AllowDynamicProperties]
class LogInController
{
    private User $user;
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
        $email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            echo "Email dhe fjalëkalimi janë të detyrueshëm.";
            return;
        }

        // Check if the user is blocked due to too many failed attempts
        if ($this->user->isBlocked($email)) {
            echo "Shumë përpjekje të dështuara. Ju lutemi prisni 30 minuta dhe provoni përsëri.";
            return;
        }

        $userId = $this->user->authenticateUser($email, $password);
        error_log("user id is: " . $userId);

        if ($userId === false) {
            // Increment failed login attempts
            try {
                $this->user->incrementFailedAttempts($email);
            } catch (Exception $e) {
                error_log("Failed to increment login attempts: " . $e->getMessage());
            }
            echo "Gabim! Email ose fjalëkalim i gabuar!";
        } else {
            // Reset failed attempts on successful login
            $this->user->resetFailedAttempts($email);

            if (!$this->user->isUserVerified($userId)) {
                echo "Përdoruesi nuk është verifikuar ende. Kontrolloni email-in tuaj.";
                return;
            }

            session_regenerate_id(true);
            $_SESSION['userId'] = $userId;
            error_log('User id, saved in the session: ' . $_SESSION['userId']);

            $_SESSION['loggedIn'] = true;

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

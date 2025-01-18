<?php
namespace App\controllers;

use App\models\Admin;
use App\models\User;
use JetBrains\PhpStorm\NoReturn;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Admin.php';

class LogInController
{
    private User $user;
    private Admin $admin;
    private PHPMailer $mailer;

    public function __construct($dbConnection)
    {
        try {
            $this->user = new User($dbConnection);
        } catch (\Exception $e) {

        }
        $this->admin = new Admin($dbConnection);
        $this->mailer = new PHPMailer(true);
    }

    public function getView(): void
    {
       // session_start();
        if ($this->checkIfLoggedIn()) {
            header('Location: /home');
            exit();
        }
        require_once 'C:/xampp/htdocs/web-project/public/login.html';
    }

    public function handleLogin(array $data): void
    {
        $email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            echo "Email dhe fjalëkalimi janë të detyrueshëm.";
            return;
        }

        // Check if credentials are for an admin
        $admin = $this->admin->authenticateAdmin($email, $password);
        if ($admin) {
            echo "Admin authenticated successfully";
            $this->startSession($admin, 'admin');
            header("Location: /admin/dashboard");
            exit();
        } else {
            echo "Not an admin";
            if ($this->user->isBlocked($email)) {
                echo "Shumë përpjekje të dështuara. Ju lutemi prisni 30 minuta dhe provoni përsëri.";
                return;
            }
            $userId = $this->user->authenticateUser($email, $password);
            if ($userId === false) {
                try {
                    $this->user->incrementFailedAttempts($email);
                } catch (\Exception $e) {
                    // Handle exception
                }
                echo "Gabim! Email ose fjalëkalim i gabuar!";
            } else {
                $this->user->resetFailedAttempts($email);

                if (!$this->user->isUserVerified($userId)) {
                    echo "Përdoruesi nuk është verifikuar ende. Kontrolloni email-in tuaj.";
                    return;
                }

                $this->startSession(['id' => $userId], 'user');
                header("Location: /home");
                exit();
            }
        }
    }


    #[NoReturn] public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /login');
        exit();
    }

    public function checkIfLoggedIn(): bool
    {
        return isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
    }

    private function startSession(array $entity, string $role): void
    {
        session_regenerate_id(true);

        if ($role === 'admin') {
            $_SESSION['adminId'] = $entity['id'];
            $_SESSION['adminLoggedIn'] = true;
        } elseif ($role === 'user') {
            $_SESSION['userId'] = $entity['id'];
            $_SESSION['loggedIn'] = true;
        }
    }
}

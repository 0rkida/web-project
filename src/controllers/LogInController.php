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
        $this->user = new User($dbConnection);
        $this->admin = new Admin($dbConnection);
        $this->mailer = new PHPMailer(true);
    }

    public function getView(): void
    {
        if ($this->checkIfLoggedIn()) {
            header('Location: /home');
            exit();
        }
        $this->checkAuthentication(); // Kontrollon nëse ka cookie të vlefshëm

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

        // Check if the user is an admin
        $admin = $this->admin->authenticateAdmin($email, $password);
        if ($admin) {
            $this->startSession($admin, 'admin');
            header("Location: /admin/dashboard");
            exit();
        }

        // Check if the user is a regular user
        if ($this->user->isBlocked($email)) {
            echo "Shumë përpjekje të dështuara. Ju lutemi prisni 30 minuta dhe provoni përsëri.";
            return;
        }

        $userId = $this->user->authenticateUser($email, $password);
        if ($userId === false) {
            $this->user->incrementFailedAttempts($email);
            echo "Gabim! Email ose fjalëkalim i gabuar!";
            return;
        }

        $this->user->resetFailedAttempts($email);

        if (!$this->user->isUserVerified($userId)) {
            echo "Përdoruesi nuk është verifikuar ende. Kontrolloni email-in tuaj.";
            return;
        }

        // Successful login for a regular user
        $this->startSession(['id' => $userId], 'user');
        header("Location: /home");
        exit();
    }

    private function checkAuthentication(): void
    {
        session_start();

        // Kontrollo nëse përdoruesi është i loguar
        if (!isset($_SESSION['userId']) && isset($_COOKIE['remember_me'])) {
            $token = $_COOKIE['remember_me'];

            // Verifiko tokenin në databazë
            $user = $this->user->verifyRememberMeToken($token);

            if ($user) {
                // Tokeni është i vlefshëm, identifiko përdoruesin
                $this->startSession(['id' => $user['id']], 'user');

                // Përditëso tokenin për siguri
                $newToken = bin2hex(random_bytes(32));
                $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));

                $this->user->saveRememberMeToken($user['id'], $newToken, $expiry);
                setcookie("remember_me", $newToken, time() + (86400 * 30), "/", "", false, true);
            } else {
                // Tokeni nuk është i vlefshëm, hiq cookie
                setcookie("remember_me", "", time() - 3600, "/", "", false, true);
            }
        }

        // Nëse përdoruesi nuk është i loguar, ridrejtoje tek login.php
        if (!isset($_SESSION['userId'])) {
            header("Location: /login");
            exit();
        }
    }


    #[NoReturn]
    public function logout(): void
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

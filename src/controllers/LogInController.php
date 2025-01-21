<?php

namespace App\controllers;

use App\models\Admin;
use App\models\User;
use App\services\PasswordResetService;
use EmailVerification;
use Exception;
use JetBrains\PhpStorm\NoReturn;


require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../services/PasswordResetService.php';
require_once __DIR__ . '/../controllers/SessionController.php';

class LogInController
{
    private User $user;
    private Admin $admin;
    private PasswordResetService $passwordResetService;
    private SessionController $sessionController;

    public function __construct($dbConnection)
    {
        try {
            $this->user = new User($dbConnection);
        } catch (Exception) {

        }
        $this->admin = new Admin($dbConnection);
        $this->passwordResetService = new PasswordResetService($dbConnection);
        $this->sessionController = new SessionController($dbConnection);
    }

    public function getView(): void
    {
        if ($this->checkIfLoggedIn()) {
            header('Location: /home');
            exit();
        }
        require_once 'C:/xampp/htdocs/web-project/public/login.html';
    }
    public function getResetPasswordView(): void
    {
        require_once __DIR__ . '/../views/reset_password.php';
        exit(); // It's good practice to exit after a header redirect.
    }

    public function getForgetPasswordView(): void {
        require_once 'C:\xampp\htdocs\web-project\public\forgot_password.html';
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

    #[NoReturn]
    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
        // Clear the remember me cookie
        setcookie('remember_me', '', time() - 900, '/'); // Expire the cookie

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

    public function passwordReset($email)
    {
        $verificationCode = $this->user->generateVerificationCode();
        // Provoni të regjistroni përdoruesin
        $resetTokenExpiry = date("Y-m-d H:i:s", strtotime('+1 hour'));
        if ($this->user->insertResetToken($email, $verificationCode, $resetTokenExpiry)) {

            require_once __DIR__.'/../helpers/EmailHelpers.php';
            EmailVerification::sendVerificationEmail($email, $verificationCode,'reset-password');
        } else {
            echo "Email-i është i zënë ose ka ndodhur një gabim gjatë regjistrimit!";
        }

    }

    public function resetPassword($token, $email, $newPassword): string
    {
        if(!$this->user->verifyResetToken($token)){
            echo 'wrong token';
            exit();
        }
        $hashed_password= password_hash($newPassword, PASSWORD_BCRYPT);
        if($this->user->updatePassword($token, $email, $hashed_password)){

            header('Location: /login');
        }else{
            header('Location: /reset-password');
        }
        exit();

    }
}


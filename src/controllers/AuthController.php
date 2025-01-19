<?php

namespace App\controllers;

use App\models\User;
use JetBrains\PhpStorm\NoReturn;

//session_start();

class AuthController
{
    public User $user;

    public function __construct($dbConnection)
    {
        $this->user = new User($dbConnection);
    }

    public function getRegisterView(): void
    {
        require_once 'C:\xampp\htdocs\web-project\public\register.html';
    }

    public function postRegister(array $data): void
    {
        $fullname = htmlspecialchars($data['fullname'] ?? '');
        $email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $username = htmlspecialchars($data['username'] ?? '');
        $password = $data['password'] ?? '';

        if (empty($fullname) || empty($email) || empty($username) || empty($password)) {
            echo "Të gjitha fushat janë të detyrueshme.";
            return;
        }

        $verificationCode = bin2hex(random_bytes(16));
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $success = $this->user->registerUser($fullname, $email, $username, $hashedPassword, $verificationCode);

        if ($success) {
            $verificationLink = "http://localhost/web-project/verify.php?verification_code=" . $verificationCode;
            echo "Regjistrimi u krye me sukses. <a href='$verificationLink'>Klikoni këtu</a> për të verifikuar llogarinë tuaj.";
        } else {
            echo "Gabim gjatë regjistrimit. Provoni përsëri.";
        }
    }

    public function getLoginView(): void
    {
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

        $userId = $this->user->authenticateUser($email, $password);

        if ($userId) {
            if (!$this->user->isUserVerified($userId)) {
                echo "Përdoruesi nuk është verifikuar ende. Kontrolloni email-in tuaj.";
                return;
            }

            session_regenerate_id(true);
            $_SESSION['userId'] = $userId;
            $_SESSION['loggedIn'] = true;

            header('Location: /home');
            exit();
        } else {
            echo "Gabim! Email ose fjalëkalim i gabuar!";
        }
    }

    public function verifyUser(string $verificationCode): void
    {
        $success = $this->user->verifyUser($verificationCode);

        if ($success) {
            echo "Verifikimi i email-it u krye me sukses! Mund të kyçeni tani.";
        } else {
            echo "Kodi i verifikimit është i pavlefshëm ose përdoruesi është verifikuar tashmë.";
        }
    }

    #[NoReturn]
    public function logout(): void
    {
        session_start();

        // Fshi token-in nga baza e të dhënave nëse ekziston një cookie 'remember_me'
        if (isset($_COOKIE['remember_me'])) {
            $token = $_COOKIE['remember_me'];
            $this->user->clearRememberMeToken($token); // Pastron token-in nga databaza

            // Fshi cookie-n
            setcookie("remember_me", "", time() - 3600, "/", "", false, true);
        }

        // Pastroni sesionin
        session_unset();
        session_destroy();

        // Ridrejtoni tek faqja e login-it
        header('Location: /login');
        exit();
    }


}
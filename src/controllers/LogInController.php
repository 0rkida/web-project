<?php

namespace App\Controllers;

use UserModel;
use JetBrains\PhpStorm\NoReturn;

session_start();

class LogInController {
    public UserModel $userModel;

    public function __construct($dbConnection) {
        $this->userModel = new UserModel($dbConnection);

    }



    public function getView(): void {
        if ($this->checkIfLoggedIn()) {
            header('Location: /home');
            exit();
        }
        require_once 'C:\xampp\htdocs\web-project\public\login.html';
    }

    public function postLogin(array $data): void {
        $email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            echo "Email dhe fjalëkalimi janë të detyrueshëm.";
            return;
        }

        $userId = $this->userModel->authenticateUser($email, $password);

        if ($userId) {
            if (!$this->userModel->isUserVerified($userId)) {
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

    public function checkIfLoggedIn(): bool {
        return isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
    }

    #[NoReturn] public function logout(): void {
        session_unset();
        session_destroy();
        header('Location: /login');
        exit();
    }
}

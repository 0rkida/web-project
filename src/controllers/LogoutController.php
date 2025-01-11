<?php


namespace App\controllers;

use JetBrains\PhpStorm\NoReturn;

session_start();

class LogoutController{

    #[NoReturn] public function logout(): void
    {
        // Unset all session variables
        session_unset();

        // Destroy the session
        session_destroy();

        // Redirect the user to the login page
        header('Location: /login');
        exit();
    }
}

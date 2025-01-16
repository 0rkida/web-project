<?php


namespace App\controllers;

use JetBrains\PhpStorm\NoReturn;

class LogoutController{

    #[NoReturn] public function logout(): void
    {
        // Start the session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Unset all session variables
        session_unset();

        // Destroy the session
        session_destroy();

        // Redirect the user to the login page
        header('Location: /login?message=logged_out');
        exit();
    }
}

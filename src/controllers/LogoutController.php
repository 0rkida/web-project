<?php


namespace App\Controllers;

use JetBrains\PhpStorm\NoReturn;

session_start();

class LogOutController{

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

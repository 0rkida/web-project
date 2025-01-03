<?php


class AuthMiddleware
{

    public static function handle():bool
    {
        session_start();

        // Set timeout duration (15 minutes)
        $sessionTimeout = 15 * 60;

        // Check if the session is set and validate its lifetime
        if (isset($_SESSION['last_activity'])) {
            if (time() - $_SESSION['last_activity'] > $sessionTimeout) {
                // Session expired
                session_unset();
                session_destroy();
                header("Location: /login");
                exit;
            }
        }

        // Update the last activity timestamp
        $_SESSION['last_activity'] = time();

        // Protecting a page
        if (!isset($_SESSION['user_email'])) {
            header("Location: /login");
            exit;
        }
        return true;
    }
}


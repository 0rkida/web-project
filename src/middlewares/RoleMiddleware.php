<?php

class RoleMiddleware
{
    public static function handle($requiredRole): bool
    {
        if (!isset($_SESSION['user_email'])) {
            header("Location: /login");
            exit;
        }

        // Check if the user is an admin
        global $conn;
        require_once implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'models', 'User.php']);
        $user = new User($conn);
        $user = $user->getByEmail($_SESSION['user_email']);

        if ($user['role'] !== $requiredRole) {
            header("Location: /login");
            exit;
        }
        return true;
    }
}
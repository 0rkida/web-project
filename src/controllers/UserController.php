<?php
namespace App\controllers;

use App\models\User;

class UserController
{
    private user $user;

    public function __construct($dbConnection)
    {
        $this->user = new User($dbConnection);
    }

    // Handles user login
    public function login($email, $password)
    {
        // Check if the user is blocked
        if ($this->user->isBlocked($email)) {
            return [
                'success' => false,
                'message' => 'Your account is temporarily blocked due to multiple failed login attempts. Please try again later.'
            ];
        }

        // Authenticate the user
        $userId = $this->user->authenticateUser($email, $password);

        if ($userId) {
            // Reset failed attempts on successful login
            $this->user->resetFailedAttempts($email);

            // Start the session and store user info
            session_start();
            $_SESSION['user_id'] = $userId;

            return [
                'success' => true,
                'message' => 'Login successful.'
            ];
        } else {
            // Increment failed attempts
            $this->user->incrementFailedAttempts($email);

            // Get the current number of failed attempts
            $failedAttempts = $this->user->getFailedAttempts($email);

            $remainingAttempts = max(0, 7 - $failedAttempts);
            $blockMessage = $remainingAttempts === 0
                ? 'Your account is now blocked for 30 minutes.'
                : "You have $remainingAttempts more attempts before your account is blocked.";

            return [
                'success' => false,
                'message' => "Invalid email or password. $blockMessage"
            ];
        }
    }
}

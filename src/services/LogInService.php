<?php

require_once 'LogInService.php';

class LogInService {
    private $LogInService;

    public function __construct($dbConnection) {
        $this->LogInService = new LogInService($dbConnection);
    }

    public function getView(): void {
        require_once 'login.html';
    }

    public function postLogin($data): void {
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            echo "Email dhe fjalëkalimi janë të detyrueshëm.";
            return;
        }

        $user = $this->LogInService->authenticateUser($email, $password);

        if ($user) {
            session_start();
            $_SESSION['user_id'] = $user['id']; // Create session for the user
            header("Location: home.php"); // Redirect to the home page
            exit();
        } else {
            echo "Email ose fjalëkalimi janë të pasakta.";
        }
    }


}

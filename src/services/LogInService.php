<?php
global $conn;
include '../db.php';

class LogInService {
    private $dbConnection;

    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    // Authenticate user by email and password
    public function authenticateUser($email, $password) {
        // Query database to find user by email and validate password
        $stmt = $this->dbConnection->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user; // Return user data if authentication is successful
        }

        return null; // Return null if authentication fails
    }
}

<?php

namespace App\controllers;

use PHPMailer\PHPMailer\PHPMailer;
use App\models\User;
use JetBrains\PhpStorm\NoReturn;

require_once __DIR__.'/../models/User.php';

class LogInController
{
    public User $user;
    private PHPMailer $mailer;

    public function __construct($dbConnection)
    {
        $this->user = new User($dbConnection);
        $this->mailer = new PHPMailer(true);
    }

    public function getView(): void
    {
        if ($this->checkIfLoggedIn()) {
            header('Location: /home');
            exit();
        }
        require_once 'C:\xampp\htdocs\web-project\public\login.html';
    }

    public function postLogin(array $data): void
    {
        // session_start();
        $email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            echo "Email dhe fjalëkalimi janë të detyrueshëm.";
            return;
        }

        $userId = $this->user->authenticateUser($email, $password);
        error_log("User ID: " . $userId);

        if ($userId === false) {
            echo "Gabim! Email ose fjalëkalim i gabuar!";
        } else {
            if (!$this->user->isUserVerified($userId)) {
                echo "Përdoruesi nuk është verifikuar ende. Kontrolloni email-in tuaj.";
                return;
            }

            session_regenerate_id(true);
            $_SESSION['userId'] = $userId;
            error_log('User ID saved in session: ' . $_SESSION['userId']);
            $_SESSION['loggedIn'] = true;

            header('Location: /home');
        }
        exit();
    }

    public function checkIfLoggedIn(): bool
    {
        return isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
    }

    #[NoReturn] public function Logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: /login');
        exit();
    }

    public function login(): void
    {
        global $conn;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Step 1: Get user_id based on username
            $query = "SELECT id FROM users WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($user_id);
            $stmt->fetch();
            $stmt->close();

            // Check if the user exists
            if (!$user_id) {
                echo "User not found.";
                exit();
            }

            // Step 2: Check failed login attempts in the last 30 minutes
            $check_failed_attempts = "SELECT COUNT(*) FROM login_attempts WHERE user_id = ? AND is_successful = 0 AND attempt_time > NOW() - INTERVAL 30 MINUTE";
            $stmt = $conn->prepare($check_failed_attempts);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($failed_count);
            $stmt->fetch();
            $stmt->close();

            // Debug: Log the failed count and the query being run
            error_log("Failed login attempts in the last 30 minutes: " . $failed_count);

            // If the user has failed 7 times in the last 30 minutes, block login attempt
            if ($failed_count >= 7) {
                echo "Too many failed login attempts. Please try again after 30 minutes.";
                exit();
            }

            // Step 3: Validate user credentials (username and password)
            $query = "SELECT password FROM users WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($stored_password);
            $stmt->fetch();
            $stmt->close();

            // Check if the entered password matches the stored password
            if (password_verify($password, $stored_password)) {
                // Step 4: Successful login, delete failed login attempts
                $delete_failed_attempts = "DELETE FROM login_attempts WHERE user_id = ? AND is_successful = 0";
                $stmt = $conn->prepare($delete_failed_attempts);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();

                // Debug: Log successful login and deletion of failed attempts
                error_log("Deleted failed login attempts for user_id: " . $user_id);

                // Set user session or perform login actions here
                $_SESSION['userId'] = $user_id;
                $_SESSION['loggedIn'] = true;

                echo "Login successful!";
                header('Location: /home');
            } else {
                // Step 5: Failed login, record attempt
                $insert_failed_attempt = "INSERT INTO login_attempts (user_id, is_successful, attempt_time) VALUES (?, 0, NOW())";
                $stmt = $conn->prepare($insert_failed_attempt);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();

                // Debug: Log the failed login attempt
                error_log("Failed login attempt for user_id: " . $user_id);

                echo "Invalid username or password.";
            }
        }
    }
}

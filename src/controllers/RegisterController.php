<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start(); // Make sure sessions are started
require_once 'C:\xampp\htdocs\web-project\vendor\autoload.php';
require_once 'C:\xampp\htdocs\web-project\src\models\User.php'; // Path to UserModel

class RegisterController {
    public UserModel $userModel;
    private $mailer;

    // Constructor that accepts DB connection and mailer service
    public function __construct($dbConnection) {
        $this->userModel = new UserModel($dbConnection);
        $this->mailer = $this->setupMailer(); // Initialize PHPMailer with Mailtrap settings
    }

    // Function to display the registration page
    public function getView(): void {
        require_once 'C:\xampp\htdocs\web-project\public\register.html'; // Path to your register.html
    }

    // Function to handle POST request for registration
    public function postRegister($data): void {
        $email = $data['email'];
        $password = $data['password'];

        // Try registering the user
        if ($this->userModel->registerUser($email, $password)) {
            // Generate verification code for email
            $verificationCode = $this->userModel->generateVerificationCode();

            // Send verification email
            if ($this->sendVerificationEmail($email, $verificationCode)) {
                echo "Registration was successful! A verification code has been sent to your email.";
            } else {
                echo "An error occurred while sending the verification email.";
            }
        } else {
            echo "The email is already taken or there was an issue with the registration!";
        }
    }

    // Function to send the verification email with the generated code
    private function sendVerificationEmail($email, $verificationCode): bool {
        $subject = 'User Verification';
        $message = "To verify your account, click the following link:\n";
        $message .= "http://yourdomain.com/verify.php?code=" . $verificationCode;

        // Set up the email headers
        $this->mailer->setFrom('no-reply@yourdomain.com');
        $this->mailer->addAddress($email);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $message;

        // Send the email using PHPMailer
        try {
            if ($this->mailer->send()) {
                return true;
            }
        } catch (Exception $e) {
            echo 'Error sending email: ' . $e->getMessage();
        }

        return false;
    }

    // Function to verify the user with the verification code
    public function verifyUser($verificationCode): void {
        if ($this->userModel->verifyUser($verificationCode)) {
            echo "User verified successfully!";
        } else {
            echo "Error! The verification code is incorrect.";
        }
    }

    // Function to set up Mailtrap with PHPMailer
    private function setupMailer(): PHPMailer {
        $mailer = new PHPMailer();

        // Mailtrap SMTP configuration
        $mailer->isSMTP();
        $mailer->Host = 'sandbox.smtp.mailtrap.io';
        $mailer->SMTPAuth = true;
        $mailer->Port = 2525;
        $mailer->Username = '0ec3624c7f0622'; // Replace with your Mailtrap username
        $mailer->Password = '********9327'; // Replace with your Mailtrap password

        return $mailer;
    }
}

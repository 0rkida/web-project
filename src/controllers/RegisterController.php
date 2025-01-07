<?php
require_once __DIR__ . '/../../vendor/autoload.php';  // Adjust the relative path if needed

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class RegisterController {
    function getView(): void {
        require_once 'C:\xampp\htdocs\web-project\public\register.html';
    }

    function postRegister($data): void {
        global $conn;
        require_once 'db.php';
        $email = $data['email'];
        $password = $data['password'];
        $verificationCode = $this->generateVerificationCode();

        // Hash the password (BCRYPT recommended)
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert user data along with verification code into the database
        $stmt = $conn->prepare("INSERT INTO users (email, password, verification_code, is_verified ) VALUES (?, ?, ?, 0 )");
        $stmt->bind_param("sss", $email, $hashedPassword, $verificationCode);

        if ($stmt->execute()) {
            // Send verification email
            $this->sendVerificationEmail($email, $verificationCode);
            echo "Registration successful! A verification code has been sent to your email.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    private function generateVerificationCode(): string {
        try {
            return bin2hex(random_bytes(32));
        } catch (Exception $e) {
            die('Could not generate verification code: ' . $e->getMessage());
        }
    }

    private function sendVerificationEmail($email, $verificationCode): void {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Përdor serverin e SMTP tuaj
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@yourdomain.com'; // Emaili juaj
            $mail->Password = 'your-password'; // Fjalëkalimi i emailit tuaj
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('no-reply@yourdomain.com', 'Your Website');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
            $mail->Body    = "Please click the link below to verify your email address:<br><a href='http://localhost/web-project/verify.php?code=" . $verificationCode . "'>Verify Email</a>";

            $mail->send();
            echo 'Verification email has been sent.';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

<?php

require __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Random\RandomException;

class EmailVerification {
    /**
     * @throws RandomException
     */
    public static function generateVerificationCode($length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    public static function sendVerificationEmail($userEmail, $verificationCode): void
    {
        $mail = new PHPMailer(true);
        $info = require_once __DIR__ . "/../../emailkeys.php";
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $info['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $info['username'];
            $mail->Password = $info['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $info['port'];

            // Recipients
            $mail->setFrom($info['email'], $info['name']);
            $mail->addAddress($userEmail);

            // Content
            $mail->isHTML();
            $mail->Subject = 'Email Verification';
            $mail->Body    = "Please verify your email by clicking the link: <a href=http://{$_SERVER['HTTP_HOST']}/verify?code=$verificationCode'>Verify Email</a>";

            $mail->send();
            echo 'Verification email has been sent';
        } catch (Exception) {
            echo "Message could not be sent. Mailer Error: $mail->ErrorInfo";
        }
    }
}

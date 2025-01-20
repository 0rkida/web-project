<?php


namespace App\services;

use App\models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__.'/../../vendor/autoload.php';


class PasswordResetService
{
    private User $user;
    private PHPMailer $mailer;

    public function __construct($dbConnection)
    {
        $this->user = new User($dbConnection);
        $this->mailer = new PHPMailer(true);
    }

    public function requestPasswordReset($email): string
    {
        if (!$this->user->isUserVerified($email)) {
            return "This email is not registered or not verified.";
        }

        $resetToken = bin2hex(random_bytes(32));
        $resetTokenExpiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        if ($this->user->saveResetToken($email, $resetToken, $resetTokenExpiry)) {
            error_log("Password reset token has been sent to " . $email);
            return $this->sendResetEmail($email, $resetToken);
        } else {
           error_log("Failed to send reset token to " . $email);
            return "Failed to save reset token.";
        }
    }

    public function sendResetEmail($email, $resetToken): string
    {
        $info = require_once __DIR__ . "/../../emailkeys.php";
        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host = $info['host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $info['username'];
            $this->mailer->Password = $info['password'];
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = $info['port'];

            // Recipients
            $this->mailer->setFrom($info['email'], $info['name']);
            $this->mailer->addAddress($email);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = "Reset Your Password";
            $this->mailer->Body = "<p>Click <a href='http://{$_SERVER['HTTP_HOST']}/reset-password?token=$resetToken'>here</a> to reset your password.</p>";

            if ($this->mailer->send()) {
                error_log("A password reset email has been sent to your email address.");
            } else {
                error_log( "Error sending email: " . $this->mailer->ErrorInfo);
            }
            return true;
        } catch (Exception $e) {
            return "Mailer Error: " . $e->getMessage();
        }
    }

    public function resetPassword($token, $newPassword): string
    {
        $userReset = $this->user->getUserByResetToken($token);

        if ($userReset) {
            $email = $userReset['email'];
            $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

            if ($this->user->updateUserPassword($email, $newPasswordHash)) {
                $this->user->deleteResetToken($token);
                return "Your password has been successfully reset.";
            } else {
                return "Failed to update password.";
            }
        } else {
            return "Invalid or expired token.";
        }
    }
}


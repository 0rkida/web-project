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
            return $this->sendResetEmail($email, $resetToken);
        } else {
            return "Failed to save reset token.";
        }
    }

    public function sendResetEmail($email, $resetToken): string
    {
        try {
            $this->mailer->isSMTP();
            $this->mailer->Host = 'smtp.gmail.com';
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = 'your_email@gmail.com';
            $this->mailer->Password = 'your_password';
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = 587;

            $this->mailer->setFrom('no-reply@yourdomain.com', 'Your App');
            $this->mailer->addAddress($email);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = "Reset Your Password";
            $this->mailer->Body = "<p>Click <a href='http://yourdomain.com/reset_password.php?token=$resetToken'>here</a> to reset your password.</p>";

            if ($this->mailer->send()) {
                return "A password reset email has been sent to your email address.";
            } else {
                return "Error sending email: " . $this->mailer->ErrorInfo;
            }
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


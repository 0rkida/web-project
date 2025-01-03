<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class EmailVerification {
    public static function generateVerificationCode($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }

    public static function sendVerificationEmail($userEmail, $verificationCode) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@example.com';
            $mail->Password = 'your_password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('your_email@example.com', 'Your Name');
            $mail->addAddress($userEmail);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
            $mail->Body    = "Please verify your email by clicking the link: <a href='https://yourdomain.com/verify.php?code=$verificationCode'>Verify Email</a>";

            $mail->send();
            echo 'Verification email has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

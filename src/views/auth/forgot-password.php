<?php
global $db;
// Include necessary files for database and PHPMailer
use App\models\User;

require '../../db.php'; // Database connection
require '../../models/User.php'; // User model
require '../../../vendor/autoload.php'; // PHPMailer autoload

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    try {
        $userModel = new User($db);
    } catch (Exception $e) {

    }

    // Check if the user exists and is verified
    if (!$user->isUserVerified($email)) {
        echo "This email is not registered or not verified.";
        exit();
    }

    // Generate a reset token and set expiry time (1 hour from now)
    $resetToken = bin2hex(random_bytes(32));
    $resetTokenExpiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

    // Save the reset token to the database
    if ($userModel->saveResetToken($email, $resetToken, $resetTokenExpiry)) {
        // Configure and send the email using PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com'; // Your SMTP email
            $mail->Password = 'your_password'; // Your SMTP password
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('no-reply@yourdomain.com', 'Your App');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Reset Your Password";
            $mail->Body = "<p>Click <a href='http://yourdomain.com/reset_password.php?token=$resetToken'>here</a> to reset your password.</p>";

            if ($mail->send()) {
                echo "A password reset email has been sent to your email address.";
            } else {
                echo "Error sending email: " . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            echo "Mailer Error: " . $e->getMessage();
        }
    } else {
        echo "Failed to save reset token.";
    }
}
?>
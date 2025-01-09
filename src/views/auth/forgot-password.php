<?php
global $db;
require '../../db.php'; // Lidhja me bazën e të dhënave
require '../../models/User.php'; // Modeli i përdoruesit
require '../../../vendor/autoload.php'; // PHPMailer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $userModel = new UserModel($db);

    // Kontrollo nëse përdoruesi ekziston
    if (!$userModel->isUserVerified($email)) {
        echo "Ky email nuk është regjistruar ose nuk është i verifikuar.";
        exit();
    }

    // Gjenero një token dhe cakto një afat
    $resetToken = bin2hex(random_bytes(32));
    $resetTokenExpiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

    // Ruaj token-in në databazë
    if ($userModel->saveResetToken($email, $resetToken, $resetTokenExpiry)) {
        // Konfiguro dhe dërgo email-in me PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com';
            $mail->Password = 'your_password';
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('no-reply@yourdomain.com', 'Your App');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Reset Your Password";
            $mail->Body = "<p>Click <a href='http://yourdomain.com/reset_password.php?token=$resetToken'>here</a> to reset your password.</p>";

            if ($mail->send()) {
                echo "Një email për rivendosjen e fjalëkalimit është dërguar.";
            } else {
                echo "Gabim gjatë dërgimit të email-it.";
            }
        } catch (Exception $e) {
            echo "Gabim: " . $e->getMessage();
        }
    } else {
        echo "Nuk u ruajt token-i.";
    }
}

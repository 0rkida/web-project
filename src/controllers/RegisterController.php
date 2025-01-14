<?php
namespace App\controllers;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\models\User;

//session_start(); // Sigurohuni që seancat janë të nisin në fillim
require_once 'C:\xampp\htdocs\web-project\vendor\autoload.php';
require_once 'C:\xampp\htdocs\web-project\src\models\User.php'; // Rruga për te UserModel

class RegisterController {
    public User $user;
//    private PHPMailer $mailer;

    // Konstruktor që merr lidhjen me bazën e të dhënave dhe shërbimin e postës
    public function __construct($dbConnection, $mailer) {
        global $mailer;
        $this->user = new User($dbConnection);
        $this->mailer = $mailer;
    }

    // Funksioni për të shfaqur faqen e regjistrimit
    public function getView(): void {
        require_once 'C:\xampp\htdocs\web-project\public\register.html'; // Vendosni rrugën e saktë për skedarin register.html
    }

    // Funksioni për të trajtuar POST request për regjistrim
    public function postRegister($data): void {
        $email = $data['email'];
        $password = $data['password'];

        $verificationCode = $this->user->generateVerificationCode();
        // Provoni të regjistroni përdoruesin
        if ($this->user->registerUser($_POST['email'], $_POST['username'], $_POST['password'], $verificationCode)) {
            // Dërgo kodin e verifikimit në email
            // Dërgo email për verifikim
            require_once __DIR__.'/../helpers/EmailHelpers.php';
            \EmailVerification::sendVerificationEmail($email, $verificationCode);
//            if ($this->sendVerificationEmail($email, $verificationCode)) {
//                echo "Regjistrimi ishte i suksesshëm! Një kod verifikimi u dërgua në email-in tuaj.";
//            } else {
//                echo "Ka ndodhur një gabim gjatë dërgimit të email-it për verifikim.";
//            }
        } else {
            echo "Email-i është i zënë ose ka ndodhur një gabim gjatë regjistrimit!";
        }
    }

    // Funksioni për dërgimin e email-it me kodin e verifikimit
//    private function sendVerificationEmail($email, $verificationCode): bool {
//        require_once __DIR__.'/../helpers/EmailHelpers.php';
//        $subject = 'Verifikimi i Përdoruesit';
//        $message = "Për të verifikuar llogarinë tuaj, klikoni në këtë link:\n";
//        $message .= "http://yourdomain.com/verify.php?code=" . $verificationCode;
//        $headers = 'From: no-reply@yourdomain.com';
//        \EmailVerification::sendVerificationEmail($email, $verificationCode);
//        // Përdorim PHPMailer për dërgimin e email-it
////        try {
////            $this->mailer->addAddress($email);
////            $this->mailer->Subject = $subject;
////            $this->mailer->Body = $message;
////
////            if ($this->mailer->send()) {
////                return true;
////            }
////        } catch (Exception $e) {
////            echo 'Gabim gjatë dërgimit të email-it: ' . $e->getMessage();
////        }
//
////        return false;
//    }

    // Funksioni për të verifikuar përdoruesin
    public function getverifyUser($verificationCode): void {
        if ($this->user->getverifyUser($verificationCode)) {
            echo "Përdoruesi u verifikua me sukses!";
        } else {
            echo "Gabim! Kodi i verifikimit nuk është i saktë.";
        }
    }
}

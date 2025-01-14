<?php
namespace App\controllers;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\models\User;

//session_start(); // Sigurohuni që seancat janë të nisin në fillim
require_once 'C:\xampp\htdocs\web-project\vendor\autoload.php';
require_once 'C:\xampp\htdocs\web-project\src\models\User.php'; // Rruga për te UserModel

class VerifyController {
    public User $user;
    private $mailer;

    // Konstruktor që merr lidhjen me bazën e të dhënave dhe shërbimin e postës
    public function __construct($dbConnection, $mailer) {
        $this->user = new User($dbConnection);
        $this->mailer = $mailer;
    }

    // Funksioni për të shfaqur faqen e verifikimit
    public function getView(): void {
        $code = $_GET['code']??"";
            require_once __DIR__.'/../views/auth/verify.php'; // Vendosni rrugën e saktë për skedarin verify.html
    }

    // Funksioni për të trajtuar verifikimin e përdoruesit
    public function postVerify($data): void {
        $email = $data['email'];
        $verificationCode = $data['code'];

        // Kontrolloni kodin e verifikimit
        if ($this->user->checkVerificationCode($email, $verificationCode)) {
            if ($this->user->markUserAsVerified($email)) {
                echo "Përdoruesi u verifikua me sukses!";
            } else {
                echo "Gabim gjatë përditësimit të statusit të verifikimit.";
            }
        } else {
            echo "Kodi i verifikimit është i gabuar ose ka skaduar.";
        }
    }

    // Funksion ndihmës për ri-dërgimin e kodit të verifikimit
    public function resendVerificationCode($email): void {
        $verificationCode = $this->user->generateVerificationCode();

        // Dërgo email me kodin e ri të verifikimit
        if ($this->sendVerificationEmail($email, $verificationCode)) {
            echo "Një kod i ri verifikimi u dërgua në email-in tuaj.";
        } else {
            echo "Ka ndodhur një gabim gjatë dërgimit të email-it për verifikim.";
        }
    }

    // Funksion për dërgimin e email-it me kodin e verifikimit
    private function sendVerificationEmail($email, $verificationCode): bool {
        $subject = 'Verifikimi i Përdoruesit';
        $message = "Për të verifikuar llogarinë tuaj, klikoni në këtë link:\n";
        $message .= "http://yourdomain.com/verify.php?code=" . $verificationCode;
        $headers = 'From: no-reply@yourdomain.com';

        // Përdorim PHPMailer për dërgimin e email-it
        try {
            $this->mailer->addAddress($email);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $message;

            if ($this->mailer->send()) {
                return true;
            }
        } catch (Exception $e) {
            echo 'Gabim gjatë dërgimit të email-it: ' . $e->getMessage();
        }

        return false;
    }
}

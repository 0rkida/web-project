<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start(); // Sigurohuni që seancat janë të nisin në fillim
require_once 'C:\xampp\htdocs\web-project\vendor\autoload.php';
require_once 'C:\xampp\htdocs\web-project\src\models\User.php'; // Rruga për te UserModel

class RegisterController {
    public UserModel $userModel;
    private PHPMailer $mailer;

    // Konstruktor që merr lidhjen me bazën e të dhënave dhe shërbimin e postës
    public function __construct($dbConnection, $mailerailer) {
        global $mailer;
        $this->userModel = new UserModel($dbConnection);
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

        // Provoni të regjistroni përdoruesin
        if ($this->userModel->registerUser($email, $password)) {
            // Dërgo kodin e verifikimit në email
            $verificationCode = $this->userModel->generateVerificationCode();

            // Dërgo email për verifikim
            if ($this->sendVerificationEmail($email, $verificationCode)) {
                echo "Regjistrimi ishte i suksesshëm! Një kod verifikimi u dërgua në email-in tuaj.";
            } else {
                echo "Ka ndodhur një gabim gjatë dërgimit të email-it për verifikim.";
            }
        } else {
            echo "Email-i është i zënë ose ka ndodhur një gabim gjatë regjistrimit!";
        }
    }

    // Funksioni për dërgimin e email-it me kodin e verifikimit
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

    // Funksioni për të verifikuar përdoruesin
    public function verifyUser($verificationCode): void {
        if ($this->userModel->verifyUser($verificationCode)) {
            echo "Përdoruesi u verifikua me sukses!";
        } else {
            echo "Gabim! Kodi i verifikimit nuk është i saktë.";
        }
    }
}

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start(); // Sigurohuni që seancat janë të nisin në fillim
require_once 'C:\xampp\htdocs\web-project\vendor\autoload.php';
require_once 'C:\xampp\htdocs\web-project\src\models\User.php'; // Sigurohuni që UserModel është i përfshirë

class LogInController {
    private UserModel $userModel;

    // Konstruktor për inicializimin e modelit dhe lidhjen me bazën e të dhënave
    public function __construct($dbConnection) {
        $this->userModel = new UserModel($dbConnection); // Inizializoni UserModel
    }

    // Funksioni për të shfaqur faqen e login
    public function getView(): void {
        if ($this->checkIfLoggedIn()) {
            // Nëse përdoruesi është i loguar, mund të bëhet redirect në faqen kryesore
            header('Location: /home');
            exit();
        }

        require_once 'C:\xampp\htdocs\web-project\public\login.html'; // Shfaq faqen login
    }

    // Funksioni për të trajtuar POST request për login
    public function postLogin($data): void {
        $email = $data['email'];
        $password = $data['password'];

        // Provoni të autentifikoni përdoruesin
        $userId = $this->userModel->authenticateUser($email, $password);

        if ($userId) {
            // Provoni të kontrolloni nëse përdoruesi është verifikuar
            $isVerified = $this->userModel->isUserVerified($userId); // Funksioni i ri për verifikimin e përdoruesit

            if (!$isVerified) {
                echo "Përdoruesi nuk është verifikuar ende. Kontrolloni email-in tuaj për kodin e verifikimit.";
                return;
            }

            // Përdoruesi është autentifikuar dhe verifikuar, ruani ID-në në sesion
            $_SESSION['userId'] = $userId;
            $_SESSION['loggedIn'] = true;

            // Redirigjoni përdoruesin në faqen kryesore (home)
            header('Location: /home');
            exit();
        } else {
            // Nëse autentifikimi dështon, shfaqni një mesazh gabimi
            echo "Gabim! Email ose fjalëkalim i gabuar!";
        }
    }

    // Funksioni për të kontrolluar nëse përdoruesi është i loguar
    public function checkIfLoggedIn(): bool {
        return isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
    }

    // Funksioni për të dalë (logout) dhe fshirë sesionin
    public function logout(): void {
        // Fshini të gjitha të dhënat e sesionit
        session_unset();
        session_destroy();

        // Redirigjoni përdoruesin në faqen e login
        header('Location: /login');
        exit();
    }
}

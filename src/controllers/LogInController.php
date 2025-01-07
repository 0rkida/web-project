<?php

class LogInController {
     // $LogInService = new LogInService();

    public function getView(): void {
        require_once 'C:\xampp\htdocs\web-project\public\login.html';
    }

    public function postLogin($data): void {
        global $conn; // Merr lidhjen me databazën
        require_once 'db.php';

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            echo "Email dhe fjalëkalimi janë të detyrueshëm.";
            return;
        }

        // Kontrollo nëse përdoruesi ekziston
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $hashedPassword = $user['password'];

            // Verifikimi i fjalëkalimit
            if (password_verify($password, $hashedPassword)) {
                session_start();
                $_SESSION['user_id'] = $user['id']; // Krijo sesion për përdoruesin
                header("Location: home.php"); // Redirect në faqen kryesore
                exit();
            } else {
                echo "Fjalëkalimi është i pasaktë.";
            }
        } else {
            echo "Email nuk ekziston.";
        }

        $stmt->close();
    }
}

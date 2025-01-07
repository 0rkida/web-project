<?php

class RegisterController {
//    $registerService = new RegisterService();

    function getView(): void{
        require_once 'register.html';
    }
    function postRegister($data): void{
        global $conn;
        require_once 'db.php';
        $email = $data['email'];
        $password = $data['password'];
        $verificationCode = $this->generateVerificationCode();

        // Hash the password (BCRYPT recommended)
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert user data along with verification code into the database
        $stmt = $conn->prepare("INSERT INTO users (email, password, verification_code, is_verified ) VALUES (?, ?, ?, 0 )");
        $username = ''; // Vendos një vlerë bosh ose një paracaktim
        $stmt->bind_param("sss", $email, $hashedPassword, $verificationCode/*, $username*/);

        if ($stmt->execute()) {
            // Send verification email
//            sendVerificationEmail($email, $verificationCode);
            echo "Registration successful! A verification code has been sent to your email.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    private function generateVerificationCode(): string {
        try {
            return bin2hex(random_bytes(32));
        } catch (Exception $e) {
            die('Could not generate verification code: ' . $e->getMessage());
        }
    }

}
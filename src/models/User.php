<?php

class UserModel {
    private $dbConnection;

    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    // Funksioni për të regjistruar përdoruesin
    public function registerUser($email, $password) {
        $verificationCode = $this->generateVerificationCode();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Kontrolloni nëse email-i është i disponueshëm
        $stmt = $this->dbConnection->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email-i është i zënë
            return false;
        }

        // Futni të dhënat e përdoruesit në bazën e të dhënave
        $stmt = $this->dbConnection->prepare("INSERT INTO users (email, password, verification_code, is_verified) VALUES (?, ?, ?, 0)");
        $stmt->bind_param("sss", $email, $hashedPassword, $verificationCode);

        if ($stmt->execute()) {
            // Përdoruesi është regjistruar me sukses
            return true;
        }

        return false;
    }

    // Funksioni për të autentikuar përdoruesin
    public function authenticateUser($email, $password) {
        $stmt = $this->dbConnection->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Krahasimi i fjalëkalimit
            if (password_verify($password, $user['password'])) {
                // Fjalëkalimi është i saktë, kthe ID-në e përdoruesit
                return $user['id'];
            }
        }

        return false; // Përdoruesi nuk u gjet ose fjalëkalimi është gabim
    }

    // Funksioni për të marrë të dhënat e përdoruesit nga ID-ja
    public function getUserById($userId) {
        $stmt = $this->dbConnection->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null; // Përdoruesi nuk u gjet
    }

    // Funksioni për të gjeneruar një kod verifikimi të rastësishëm
    public function generateVerificationCode() {
        try {
            return bin2hex(random_bytes(32));
        } catch (Exception $e) {
            die('Nuk mund të gjenerohet kodi i verifikimit: ' . $e->getMessage());
        }
    }

    // Funksioni për të verifikuar përdoruesin duke përdorur kodin e verifikimit
    public function verifyUser($verificationCode) {
        $stmt = $this->dbConnection->prepare("UPDATE users SET is_verified = 1 WHERE verification_code = ?");
        $stmt->bind_param("s", $verificationCode);
        return $stmt->execute();
    }
    public function isUserVerified($email){
        $stmt = $this->dbConnection->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        return $stmt->execute();



    }
}

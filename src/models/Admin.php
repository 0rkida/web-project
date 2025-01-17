<?php

namespace App\models;
class Admin
{
    private $db;

    public function __construct($conn)
    {
        $this->db = $conn;
    }
    public function getAdmin() {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE role = 'Admin' LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


public function createAdmin($data) {
        // Kontrollo nëse admini ekziston
        if ($this->getAdmin()) {
            return false; // Admini ekziston, nuk mund të krijohet një tjetër
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'Admin')");
        $stmt->bind_param("ssss", $data['full_name'], $data['email'], $hashedPassword);
        return $stmt->execute();
    }



    public static function authenticateAdmin($email, $password) {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['password'])) {
                return $admin;  // Return admin data if login is successful
            }
        }
        return false;  // Return false if login fails
    }

    public function updateAdmin($data) {
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE users SET full_name = ?, email = ?, password = ? WHERE role = 'Admin'");
        $stmt->bind_param("sss", $data['name'], $data['email'], $hashedPassword);
        return $stmt->execute();
    }

}



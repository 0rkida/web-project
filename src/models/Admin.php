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
        $stmt = $this->db->prepare("INSERT INTO admins (full_name, email, password) VALUES ( ?, ?, ?)");
        $stmt->bind_param("ssss", $data['full_name'], $data['email'], $hashedPassword);
        return $stmt->execute();
    }



    public function authenticateAdmin($email, $password) {
        // Prepare a query to check for an admin
        $sql = "SELECT * FROM users WHERE email = ? AND role = 'admin'";
        $stmt = $this->db->prepare($sql);

        // Bind the email parameter (assuming email is a string, s for string)
        $stmt->bind_param("s", $email);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the admin details
        $admin = $result->fetch_assoc();

        // Verify password
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }

        return false;  // Return false if the user is not an admin or the password is incorrect
    }





    public function updateAdmin($data) {
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE admins SET full_name = ?, email = ?, password = ? WHERE id=? ");
        $stmt->bind_param("sss", $data['name'], $data['email'], $hashedPassword);
        return $stmt->execute();
    }

}



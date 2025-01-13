<?php
require 'AuthController.php';

class Auth{
    private $conn;
    public function __construct($dbConnection){
        $this->conn = $dbConnection;
    }
    public function registerUser($fullname, $email,$username, $hashedPassword,$verification_code){
        $stmt= $this->conn->prepare("INSERT INTO users (full_name,email,username,$hashedPassword,verification_code) VALUES (?,?,?,?,?)");
        $stmt->bindParam("sssss",$fullname,$email,$username,$hashedPassword,$verification_code);
        $stmt->execute();
    }
    public function loginUser($email,$password){
        $stmt= $this->conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
       return  $stmt->execute();

        }
        public function findbyUsername($username){
        $stmt= $this->conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        }
        public function verifyEmail($verification_code)
        {
            $stmt= $this->conn->prepare("UPDATE users SET is_verified=1 WHERE verification_code=?");
            $stmt->bind_param("s", $verification_code);
            $stmt->execute();
            return $stmt -> affected_rows;

        }

}

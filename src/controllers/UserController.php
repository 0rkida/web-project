<?php
namespace App\controllers;
use AllowDynamicProperties;
use App\models\User;
use Database;
use PDO;
use SessionController;

require_once 'C:\xampp\htdocs\web-project\src\db.php';
require_once '../models/User.php';
require_once '../controllers/SessionController.php';
include 'sessionManager.php';

#[AllowDynamicProperties] class UserController {
    public User $user;
    private SessionController $sessionController;
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
        $this->sessionController = new SessionController($this->db);

    }

    public function register($data)  {
        global $email, $password;
        $this->user->full_name = $data['full_name'];
        $this->user->email = $data['email'];
        $this->user->password = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->user->role = 'user';
        $this->user->created_at = date('Y-m-d H:i:s');

        if ($this->user->register($email, $password)) {
            return ['status' => true, 'message' => 'User registered successfully.'];
        } else {
            return ['status' => false, 'message' => 'User registration failed.'];
        }
    }

    public function login($email, $password) {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Start session and insert session data into the database
            $sessionController = new SessionController($this->db);
            $sessionController->startSession($user['id']);

            return ['status' => true, 'message' => 'Login successful.'];
        } else {
            return ['status' => false, 'message' => 'Invalid email or password.'];
        }
    }


}

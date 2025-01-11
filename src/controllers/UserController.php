<?php
namespace App\controllers;
use AllowDynamicProperties;
use App\models\User;
use Database;
use PDO;

require_once 'C:\xampp\htdocs\web-project\src\db.php';
require_once '../models/User.php';


#[AllowDynamicProperties] class UserController {
    public User $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
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

    public function login($email, $password, $remember_me) :bool {
        // Check if the email exists in the database
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login successful: Start a session
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Handle "Remember Me" functionality
            if ($remember_me) {
                // Generate a random token
                $remember_token = bin2hex(random_bytes(32));

                // Store the token in the database
                $query = "UPDATE password_resets SET remember_token = :remember_token WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(":remember_token", $remember_token);
                $stmt->bindParam(":id", $user['id']);
                $stmt->execute();

                // Set the token as a cookie in the user's browser
                setcookie("remember_me", $remember_token, time() + (86400 * 30), "/"); // Expires in 30 days
            }

            return ['status' => true, 'message' => 'Login successful.'];
        } else {
            return ['status' => false, 'message' => 'Invalid email or password.'];
        }
    }

}

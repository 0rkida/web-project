<?php
require_once '../config/Database.php';
require_once '../models/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function register($data) {
        $this->user->full_name = $data['full_name'];
        $this->user->email = $data['email'];
        $this->user->password = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->user->role = 'user';
        $this->user->created_at = date('Y-m-d H:i:s');

        if ($this->user->register()) {
            return ['status' => true, 'message' => 'User registered successfully.'];
        } else {
            return ['status' => false, 'message' => 'User registration failed.'];
        }
    }
}

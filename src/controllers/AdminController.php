<?php
namespace App\controllers;

use App\models\Admin;

class AdminController {
    private Admin $admin;

    public function __construct($dbConnection) {
        $this->admin = new Admin($dbConnection);
    }

    // Render the admin dashboard page
    public function getView(): void
    {
        if ($this->checkIfLoggedIn()) {
            header('Location: /dashboard');
            exit();
        }
        require_once 'C:\xampp\htdocs\web-project\src\admin-dashboard.html';
    }

    // Handle admin creation (registration)
    public function createAdmin($data): void
    {
        if ($this->admin->createAdmin($data)) {
            // Admin created successfully
            header("Location: /admin-dashboard.html");
        } else {
            // Handle error (admin already exists, etc.)
            echo "Error: Admin already exists.";
        }
    }

    // Handle admin login
    public function authenticateAdmin($email, $password) {
        $admin = $this->admin->authenticateAdmin($email, $password);
        if ($admin) {
            // Login successful, store session data
            $_SESSION['admin'] = $admin;
            header("Location: /admin-dashboard.html");
        } else {
            // Handle authentication failure
            echo "Invalid email or password.";
        }
    }

    // Handle admin update
    public function updateAdmin($data) {
        if ($this->admin->updateAdmin($data)) {
            // Admin updated successfully
            header("Location: /admin-dashboard.html");
        } else {
            // Handle error
            echo "Error updating admin.";
        }
    }

    // Method to load views (you can customize this method as needed)
    private function loadView($viewName, $data = []) {
        extract($data); // Extract data into variables for the view
        include 'views/' . $viewName . '.php'; // Include the view file
    }

    public function checkIfLoggedIn(): bool
    {
        return isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
    }

}

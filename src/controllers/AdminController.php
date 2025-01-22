<?php
namespace App\controllers;

use App\models\Admin;
require_once __DIR__.'/../models/Admin.php';
class AdminController {
    private Admin $admin;
    private User $user;

    public function __construct($dbConnection) {
        $this->admin = new Admin($dbConnection);
        $this->user = new User ($dbConnection);
    }

    // Render the admin dashboard page
    public function getView(): void
    {
        if ($this->checkIfLoggedIn()) {
            header('Location: /dashboard');
            exit();
        }
        require_once __DIR__.'/../models/User.php';

       $users = $this->user->getAllUsers();
    }
        require_once __DIR__ . "/../views/admin-dashboard.php";
    }




    public function checkIfLoggedIn(): bool
    {
        return isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
    }

}

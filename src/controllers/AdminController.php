<?php
namespace App\controllers;

use App\models\Admin;
use App\models\User;
use Exception;

require_once __DIR__.'/../models/Admin.php';
require_once __DIR__.'/../models/User.php';
class AdminController {
    private Admin $admin;
    private User $user;

    public function __construct($dbConnection) {
        $this->admin = new Admin($dbConnection);
        try {
            $this->user = new User ($dbConnection);
        } catch (Exception) {

        }
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
        $userCount = $this->user->getUserCount();
        $likesCount = $this->user->getLikesCount();
        $matchesCount = $this->user->getMatchesCount();

        require_once __DIR__ . "/../views/admin-dashboard.php";
    }




    public function checkIfLoggedIn(): bool
    {
        return isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
    }

}

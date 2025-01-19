<?php
// SearchController.php

namespace App\controllers;

use App\models\User;

class SearchController
{
    private $userModel;

    public function __construct($dbConnection)
    {
        $this->userModel = new User($dbConnection);
    }

    public function search()
    {
        if (isset($_GET['name'])) {
            $name = htmlspecialchars($_GET['name']);
            $results = $this->userModel->searchByName($name);

            // Load the search results view
            include '../views/searchResults.php';
        } else {
            // Redirect to home if no name is provided
            header('Location: /home');
        }
    }
}
?>

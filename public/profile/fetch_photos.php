<?php

// Include your controller or database connection
require_once 'src/controllers/ProfileController.php';

// Get user ID from session
$userId = $_SESSION['userId'] ?? null;

// Fetch the user's photos
$photos = [];
if ($userId) {
    $photos = $this->getPictures($userId); // Adjust this call as per your logic
}


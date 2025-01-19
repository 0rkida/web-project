<?php
session_start(); // Start the session

// Clear the remember me cookie
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, "/"); // Expire the cookie
}

// Clear the token from the database
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

if (isset($_SESSION['user_id'])) {
    $query = "UPDATE users SET remember_me_token = NULL WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $_SESSION['user_id']);
    $stmt->execute();
}

// Destroy the session
session_unset(); // Clear all session variables
session_destroy(); // Destroy the session

// Redirect to login page
header("Location: login.php");
exit;

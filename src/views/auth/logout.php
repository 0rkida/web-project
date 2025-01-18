<?php



session_start();
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session
header("Location: login.html"); // Redirect to login page
exit();


session_start();

// Clear the remember me cookie
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, "/"); // Expire the cookie
}

// Clear the token from the database
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

if (isset($_SESSION['user_id'])) {
    $query = "UPDATE users SET remember_token = NULL WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $_SESSION['user_id']);
    $stmt->execute();
}

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: login.php");
exit;



<?php
global $conn;
require '../../db.php';

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'datting_app'; // Use the correct database name

// Sample admin details
$full_name = 'Holta Ozuni';
$email = 'entelaozuni00@gmail.com';
$password = 'Canabis45 ';  // Plain text password (make sure to change this)

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Database connection
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the insert query
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Ensure admin details are added correctly
    $sql = "INSERT INTO admins (full_name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $full_name, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "New admin created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

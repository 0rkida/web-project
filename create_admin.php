<?php

// Sample admin details
$full_name = 'Holta Ozuni';
$email = 'entelaozuni00@gmail.com';
$password = 'Canabis45 ';  // Plain text password (make sure to change this)

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Database connection
$conn = new mysqli("localhost", "root", "root", "test");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Prepare and execute the insert query
$sql = "INSERT INTO admins (full_name, email, password) VALUES ('$full_name', '$email', '$hashedPassword')";

if ($conn->query($sql) === TRUE) {
    echo "New admin created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();


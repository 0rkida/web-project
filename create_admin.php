<?php

// Sample admin details
$full_name = 'Holta Ozuni';
$email = 'holtaozuni12@gmail.com';
$password = 'Canabis45';  // Plain text password (make sure to change this)

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Database connection (make sure to replace these values with your actual database credentials)
$servername = "localhost";
$username = "root";  // Your MySQL username
$password_db = "";  // Your MySQL password
$dbname = "datting_app";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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


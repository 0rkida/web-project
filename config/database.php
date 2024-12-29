<?php
$host = 'localhost';
$dbname = 'dating_app';
$username = 'root';
$password = ''; // Leave blank for XAMPP

// Create connection
/** @var TYPE_NAME $servername */
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
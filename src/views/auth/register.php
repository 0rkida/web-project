<?php
// Start the session
session_start();

// Include the database connection file
include '../config/db.php'; // Adjust the path if needed

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
}
// Validate inputs
if (empty($name) || empty($email) || empty($password)) {
    die("All fields are required.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}

if (strlen($password) < 6) {
    die("Password must be at least 6 characters long.");
}
// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert into the database
$conn = null;
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    echo "Registration successful!";
    header("Location: login.php"); // Redirect to login page
    exit;
} else {
    echo "Error: " . $stmt->error;
}
?>

?>

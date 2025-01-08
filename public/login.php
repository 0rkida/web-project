<?php

// Start the session if you're using sessions
session_start();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // In a real-world application, you would check the credentials against a database
    // For now, we'll use hardcoded credentials for testing purposes
    $validEmail = "user@example.com";
    $validPassword = "password123"; // Hash passwords in production

    // Check if the email and password match the valid credentials
    if ($email === $validEmail && $password === $validPassword) {
        // Successful login, create a session or other login actions
        $_SESSION['user'] = $email;
        // Redirect to a logged-in page (e.g., dashboard)
        header('Location: dashboard.php');
        exit;
    } else {
        // Invalid credentials
        echo "Invalid email or password!";
    }
}

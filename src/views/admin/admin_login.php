<?php

// Database connection
$conn = new mysqli("localhost", "root", "root", "test");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to get the admin data
    $query = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Check the password using password_verify
        if (password_verify($password, $admin['password'])) {
            // Password is correct, proceed with login
            $_SESSION['role'] = 'Admin';  // Set the session role to 'Admin'
            header("Location: /admin/dashboard");  // Redirect to the admin dashboard
            exit;
        } else {
            // Incorrect password
            echo "Wrong password";
        }
    } else {
        // No user found with that email
        echo "Wrong email";
    }

    // Close the connection
    $conn->close();
}



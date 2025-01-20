<?php
session_start();
include 'config/db_connection.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = new mysqli("localhost", "root", "", "datting_app"); // Update with the correct database name

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$profile_picture_path = null;

// Handle file upload for profile picture
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'public/assets/img/user-uploads/albums/';
    $fileName = basename($_FILES['profile_picture']['name']);
    $profile_picture_path = $uploadDir . $fileName;

    // Move the uploaded file to the desired directory
    if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture_path)) {
        die("Failed to upload the profile picture.");
    }
}

// Prepare the profile update query
$query = "UPDATE profile SET 
            full_name = ?, 
            email = ?, 
            age = ?, 
            gender = ?, 
            location = ?, 
            self_summary = ?, 
            hobby = ?, 
            doing_with_life = ?, 
            good_at = ?, 
            ethnicity = ?, 
            height = ?" .
    ($profile_picture_path ? ", picture_path = ?" : "") .
    " WHERE user_id = ?";

$stmt = $conn->prepare($query);

// Bind parameters dynamically
if ($profile_picture_path) {
    $stmt->bind_param(
        "ssissssssssi",
        $_POST['full_name'], $_POST['email'], $_POST['age'], $_POST['gender'],
        $_POST['location'], $_POST['self_summary'], $_POST['hobby'],
        $_POST['doing_with_life'], $_POST['good_at'], $_POST['ethnicity'],
        $_POST['height'], $profile_picture_path, $user_id
    );
} else {
    $stmt->bind_param(
        "ssisssssssi",
        $_POST['full_name'], $_POST['email'], $_POST['age'], $_POST['gender'],
        $_POST['location'], $_POST['self_summary'], $_POST['hobby'],
        $_POST['doing_with_life'], $_POST['good_at'], $_POST['ethnicity'],
        $_POST['height'], $user_id
    );
}

// Execute and check the result
if ($stmt->execute()) {
    header("Location: profil?status=success"); // Redirect to profile page with success message
    exit();
} else {
    echo "Error updating profile: " . $conn->error;
}

$stmt->close();
$conn->close();

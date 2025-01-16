<?php
include 'session_check.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_POST['user_id'];
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$password = ($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
$profile_picture = $_FILES['picture_path']['name'];
$profile_picture_tmp = $_FILES['picture_path']['tmp_name'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$location = $_POST['location'];
$self_summary = $_POST['self_summary'];
$hobby = $_POST['hobby'];
$doing_with_life = $_POST['doing_with_life'];
$good_at = $_POST['good_at'];
$ethnicity = $_POST['ethnicity'];
$height = $_POST['height'];


// Handle file upload for profile picture
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    // File was uploaded successfully
    $profile_picture_tmp = $_FILES['profile_picture']['tmp_name'];
    $profile_picture_name = $_FILES['profile_picture']['name'];
    $uploads_dir = 'public/assets/img/user-uploads/albums/'; // Assuming this is where the images are stored

    // Create a unique name for the image
    $picture_path = uniqid('photo_') . '.' . pathinfo($profile_picture_name, PATHINFO_EXTENSION);

    // Move the uploaded file to the desired location
    if (move_uploaded_file($profile_picture_tmp, $uploads_dir . $picture_path)) {
        // File upload was successful, $picture_path contains the path
        echo "Profile picture uploaded successfully.";
    } else {
        // Error during file upload
        echo "Error moving the uploaded file.";
    }
} else {
    // No new file uploaded, keep the existing profile picture if not changed
    $picture_path = $_POST['existing_profile_picture'];  // Assuming you have a form field for the existing picture path
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "datting_app");  // Use the correct database name

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update the user's profile picture in the database
$user_id = $_SESSION['userId']; // Assuming user ID is stored in session
$query = "UPDATE user_pictures SET picture_path = ? WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $picture_path, $user_id); // "si" means string and integer
$stmt->execute();

// Handle errors or success
if ($stmt->affected_rows > 0) {
    echo "Profile picture updated successfully in the database.";
} else {
    echo "Error updating profile picture in the database.";
}


session_start();
include("config/db_connection.php"); // Include your database connection script

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user data from POST request
    $self_summary = $_POST['self_summary'];

    // Assume you have the logged-in user ID in the session
    $user_id = $_SESSION['user_id'];

    // Update self-summary in the database
    $query = "UPDATE profile SET self_summary = :self_summary WHERE user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':self_summary', $self_summary);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile!";
    }
} else {
    echo "Invalid request method.";
}



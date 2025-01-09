<?php
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
$profile_picture = $_FILES['profile_picture']['name'];
$profile_picture_tmp = $_FILES['profile_picture']['tmp_name'];
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
if ($profile_picture) {
    $profile_picture_path = 'uploads/' . $profile_picture;
    move_uploaded_file($profile_picture_tmp, $profile_picture_path);
} else {
    $profile_picture_path = $_POST['existing_profile_picture'];  // Keep existing picture if not changed
}

$conn = new mysqli("localhost", "root", "", "02_create_profiles_table.sql");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update profile in the database
$sql = "UPDATE profile SET full_name='$full_name', email='$email', age='$age', gender='$gender', location='$location', 
        self_summary='$self_summary', hobby='$hobby', doing_with_life='$doing_with_life', good_at='$good_at', 
        ethnicity='$ethnicity', height='$height', profile_picture='$profile_picture_path'";

if ($password) {
    $sql .= ", password='$password'";
}

$sql .= " WHERE id='$user_id'";

if ($conn->query($sql) === TRUE) {
    echo "Profile updated successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();


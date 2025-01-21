<?php
include 'sessionManager.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "datting_app");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Fetch user profile data from the database
$sql = "SELECT * FROM profile WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
</head>
<body>

<h1>Profile of <?php echo $user['full_name']; ?></h1>
<img src="uploads/<?php echo $user['profile_picture']; ?>" alt="Profile Picture" width="100">
<p>Email: <?php echo $user['email']; ?></p>
<p>Age: <?php echo $user['age']; ?></p>
<p>Gender: <?php echo $user['gender']; ?></p>
<p>Location: <?php echo $user['location']; ?></p>
<p>Self Summary: <?php echo nl2br($user['self_summary']); ?></p>
<p>Hobbies: <?php echo nl2br($user['hobby']); ?></p>
<p>Doing with Life: <?php echo nl2br($user['doing_with_life']); ?></p>
<p>Good At: <?php echo nl2br($user['good_at']); ?></p>
<p>Ethnicity: <?php echo $user['ethnicity']; ?></p>
<p>Height: <?php echo $user['height']; ?></p>

<a href="profil/update/">Update Info</a>

</body>
</html>

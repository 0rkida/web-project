<?php
include 'session_check.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "your_database_name");

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

<h1>Profile of <?php echo htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8'); ?></h1>
<img src="uploads/<?php echo htmlspecialchars($user['profile_picture'], ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Picture" width="100">
<p>Email: <?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></p>
<p>Age: <?php echo htmlspecialchars($user['age'], ENT_QUOTES, 'UTF-8'); ?></p>
<p>Gender: <?php echo htmlspecialchars($user['gender'], ENT_QUOTES, 'UTF-8'); ?></p>
<p>Location: <?php echo htmlspecialchars($user['location'], ENT_QUOTES, 'UTF-8'); ?></p>
<p>Self Summary: <?php echo nl2br(htmlspecialchars($user['self_summary'], ENT_QUOTES, 'UTF-8')); ?></p>
<p>Hobbies: <?php echo nl2br(htmlspecialchars($user['hobby'], ENT_QUOTES, 'UTF-8')); ?></p>
<p>Doing with Life: <?php echo nl2br(htmlspecialchars($user['doing_with_life'], ENT_QUOTES, 'UTF-8')); ?></p>
<p>Good At: <?php echo nl2br(htmlspecialchars($user['good_at'], ENT_QUOTES, 'UTF-8')); ?></p>
<p>Ethnicity: <?php echo htmlspecialchars($user['ethnicity'], ENT_QUOTES, 'UTF-8'); ?></p>
<p>Height: <?php echo htmlspecialchars($user['height'], ENT_QUOTES, 'UTF-8'); ?></p>

<a href="edit_profile.php">Edit Profile</a>

</body>
</html>

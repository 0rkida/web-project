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

// Fetch user profile data
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
    <title>Edit Profile</title>
</head>
<body>

<h1>Edit Profile</h1>

<form action="update_profile.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

    <label for="full_name">Full Name:</label>
    <input type="text" id="full_name" name="full_name" value="<?php echo $user['full_name']; ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">

    <label for="profile_picture">Profile Picture:</label>
    <input type="file" id="profile_picture" name="profile_picture">

    <label for="age">Age:</label>
    <input type="number" id="age" name="age" value="<?php echo $user['age']; ?>">

    <label for="gender">Gender:</label>
    <select id="gender" name="gender">
        <option value="male" <?php if($user['gender'] == 'male') echo 'selected'; ?>>Male</option>
        <option value="female" <?php if($user['gender'] == 'female') echo 'selected'; ?>>Female</option>
    </select>

    <label for="location">Location:</label>
    <input type="text" id="location" name="location" value="<?php echo $user['location']; ?>">

    <label for="self_summary">Self Summary:</label>
    <textarea id="self_summary" name="self_summary"><?php echo $user['self_summary']; ?></textarea>

    <label for="hobby">Hobbies:</label>
    <textarea id="hobby" name="hobby"><?php echo $user['hobby']; ?></textarea>

    <label for="doing_with_life">Doing with Life:</label>
    <textarea id="doing_with_life" name="doing_with_life"><?php echo $user['doing_with_life']; ?></textarea>

    <label for="good_at">Good At:</label>
    <textarea id="good_at" name="good_at"><?php echo $user['good_at']; ?></textarea>

    <label for="ethnicity">Ethnicity:</label>
    <input type="text" id="ethnicity" name="ethnicity" value="<?php echo $user['ethnicity']; ?>">

    <label for="height">Height:</label>
    <input type="text" id="height" name="height" value="<?php echo $user['height']; ?>">

    <button type="submit">Update Profile</button>
</form>

</body>
</html>

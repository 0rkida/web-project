<?php
// fetch_users.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datting_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT users.full_name, profile.user_id, profile.profile_picture, profile.last_online 
        FROM profile 
        JOIN users ON profile.user_id = users.id";
$result = $conn->query($sql);

$users = array();
while($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);

$conn->close();
?>
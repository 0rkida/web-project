<?php

//query for all users
global $conn;
$query = "SELECT * FROM users";
$result = $conn->query($query);

//ad an user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $query = "INSERT INTO users (username, email, role, password) VALUES ('$username', '$email', '$role', '$password')";
    $conn->query($query);
}


//delete user
if (isset($_POST['delete_user'])) {
    $userId = $_POST['user_id'];
    $query = "DELETE FROM users WHERE id = $userId";
    $conn->query($query);
}






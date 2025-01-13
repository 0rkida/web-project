<?php

use Random\RandomException;

require 'db.php';
function registerUser($fullname, $email, $password): string
{
    global $conn;
    try {
        $verification_code = bin2hex(random_bytes(16));
    } catch (RandomException) {

    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt=$conn->prepare("INSERT INTO users(full_name, email, username, password, verification_code) VALUES (?,?,?,?,?)");
    $stmt->bind_param("ssss" ,$fullname, $email, $username, $hashed_password, $verification_code);
    if($stmt->execute()) {
        $verification_link = "http://localhost/web-project/verify.php?verification_code=" . $verification_code;
        return "User registered successfully. <a href='$verification_link'>Click here</a> to verify your account.";
    }else{
        return "Error while registering. Try again.";

    }

}
function login($email, $password): string
{
    global $conn;

    $stmt=$conn->prepare("SELECT * FROM users WHERE email=? AND password=?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if($user['is_verified'] == 0) {
            return "Account not verified. Please try again.";
        }
        if(password_verify($password, $user['password'])) {
            return "Login successfol! Welcome." . $user['full_name'];
        }else{
            return "Login failed. Try again.";
        }

    }else{
        return "User not found. Try again.";
    }
}
function verify($verification_code): string
{
    global $conn;
    $stmt=$conn->prepare("UPDATE  users SET is_verified=1 where verification_code=?");
    $stmt->bind_param("s", $verification_code);
    if($stmt->execute() && $stmt->affected_rows > 0) {
        return "Email verification successful! You can now login.";

    }else{
        return "Invalid verification code or user already verified.";
    }
}



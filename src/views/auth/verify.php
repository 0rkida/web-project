<?php
include '../includes/db.php';  // Include your database connection
include '../Verification.php';   // Include the Verification class

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $verificationCode = $_POST['verification_code'];

    Verification::verifyEmail($verificationCode);  // Call the method from Verification class
}



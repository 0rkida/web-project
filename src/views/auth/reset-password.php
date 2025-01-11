<?php
// Include necessary files
global $db;
require '../../db.php';  // Ensure your DB connection is correctly set here
require '../../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate user input
    $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (empty($token) || empty($password)) {
        echo "Ju lutem, plotësoni të gjitha fushat.";
        exit();
    }

    $userModel = new UserModel($db);

    // Verify reset token
    if ($userModel->verifyResetToken($token)) {
        // Hash the new password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Update the password in the database
        if ($userModel->updatePassword($token, $hashedPassword)) {
            echo "Fjalëkalimi u rivendos me sukses.";
            header("Location: ../../../login.html");
            exit();
        } else {
            echo "Gabim gjatë rivendosjes së fjalëkalimit. Provoni përsëri.";
        }
    } else {
        echo "Token i pavlefshëm ose i skaduar.";
    }
}

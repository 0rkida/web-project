<?php
global $db;
require '../../db.php';
require '../../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $userModel = new UserModel($db);

    if ($userModel->verifyResetToken($token)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        if ($userModel->updatePassword($token, $hashedPassword)) {
            echo "Fjalëkalimi u rivendos me sukses.";
            header("Location: ../../../login.html");
        } else {
            echo "Gabim gjatë rivendosjes së fjalëkalimit.";
        }
    } else {
        echo "Token i pavlefshëm ose i skaduar.";
    }
}

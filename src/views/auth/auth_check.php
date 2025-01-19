<?php
session_start();
use App\models\User;

// Kontrollo nëse përdoruesi është tashmë i loguar
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];

    // Verifiko tokenin në databazë
    $user = User::verifyRememberMeToken($token);

    if ($user) {
        // Tokeni është i vlefshëm, identifiko përdoruesin
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();

        // Përditëso tokenin për siguri
        $newToken = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));

        User::saveRememberMeToken($user['id'], $newToken, $expiry);
        setcookie("remember_me", $newToken, time() + (86400 * 30), "/", "", false, true);
    } else {
        // Tokeni nuk është i vlefshëm, hiq cookie
        setcookie("remember_me", "", time() - 3600, "/", "", false, true);
    }
}

// Nëse përdoruesi nuk është i loguar, ridrejtoje tek login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}
?>

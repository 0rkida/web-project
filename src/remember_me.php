<?php
global $conn;
if (isset($_COOKIE["remember_me"])) {
    $token = $_COOKIE["remember_me"];


    // Query the database for the token
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Regenerate the token for security (optional but recommended)
        $newToken = bin2hex(random_bytes(32));


        // Update the token in the database
        $updateStmt = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
        $updateStmt->bind_param("ssi", $newToken,  $user['id']);
        $updateStmt->execute();

        // Set the updated cookie
        setcookie(
            "remember_me",
            $newToken,
            [
                'path' => '/',
                'domain' => '', // Add your domain if needed, e.g., ".example.com"
                'secure' => true, // Set to true for HTTPS
                'httponly' => true, // Prevent JavaScript access
                'samesite' => 'Strict', // Helps prevent CSRF attacks
            ]
        );
        // Log the user in
        session_start();
        $_SESSION["user_id"] = $user['id'];
        $_SESSION["username"] = $user['username'];
        global $request_path;
        if(strtolower($request_path) === "/login" || strtolower($request_path) === "/register"){
            header('Location: /');
            exit();
        }
    } else {
        // Token is invalid or expired, clear the cookie
        setcookie("remember_me", "", [
            'path' => '/',
            'domain' => '', // Match the domain used in the cookie
            'secure' => true,
            'httponly' => true,
            'samesite' => 'String',
        ]);
    }

    // Close the prepared statements and connection
    $stmt->close();
    if (isset($updateStmt)) {
        $updateStmt->close();
    }

}

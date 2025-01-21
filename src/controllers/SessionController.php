<?php
namespace App\controllers;
class SessionController {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // This function starts the session and inserts the session data into the database
    public function startSession($userId) {
        session_start();
        $_SESSION['user_id'] = $userId;

        // Insert session into user_sessions table
        $query = "INSERT INTO user_sessions (user_id, last_activity, session_start) VALUES (:user_id, NOW(), NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        // Store the session_id from the database to the session
        $_SESSION['session_id'] = $this->db->lastInsertId();
    }

    // This function checks the session for activity and expires the session if needed
    public function checkSession() {
        session_start();

        if (isset($_SESSION['session_id'])) {
            $sessionId = $_SESSION['session_id'];

            // Check the last activity time from the database
            $query = "SELECT last_activity, session_timeout FROM user_sessions WHERE session_id = :session_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':session_id', $sessionId);
            $stmt->execute();
            $session = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($session) {
                $lastActivity = strtotime($session['last_activity']);
                $sessionTimeout = $session['session_timeout'];
                $currentTime = time();

                // Check if the session has expired
                if (($currentTime - $lastActivity) > $sessionTimeout) {
                    // Session expired, destroy session and remove from the database
                    session_unset();
                    session_destroy();

                    $query = "UPDATE user_sessions SET is_active = FALSE WHERE session_id = :session_id";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':session_id', $sessionId);
                    $stmt->execute();

                    header('Location: /login'); // Redirect to login page
                    exit();
                } else {
                    // Update the last activity time
                    $query = "UPDATE user_sessions SET last_activity = NOW() WHERE session_id = :session_id";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':session_id', $sessionId);
                    $stmt->execute();
                }
            } else {
                session_unset();
                session_destroy();
                header('Location: /login'); // If no session found, redirect to login
                exit();
            }
        } else {
            session_unset();
            session_destroy();
            header('Location: /login'); // If no session found, redirect to login
            exit();
        }
    }
}

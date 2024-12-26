<?php

require 'Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reportedUserId = $_POST['reported_user_id'];
    $action = $_POST['action'];
    $reason = $_POST['reason'];
    $reportingUserId = $_SESSION['user_id']; // Assuming the user is logged in

    $conn = Database::getConnection();

    if ($action == 'report') {
        // Insert report into the database
        $sql = "INSERT INTO reports (reporting_user_id, reported_user_id, reason, created_at) VALUES ('$reportingUserId', '$reportedUserId', '$reason', NOW())";
        mysqli_query($conn, $sql);
        echo "User has been reported.";
    } elseif ($action == 'block') {
        // Insert block into the database
        $sql = "INSERT INTO blocks (blocking_user_id, blocked_user_id, created_at) VALUES ('$reportingUserId', '$reportedUserId', NOW())";
        mysqli_query($conn, $sql);
        echo "User has been blocked.";
    }
}
?>
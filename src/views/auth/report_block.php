<?php
require_once './src/db.php';
session_start(); // Start the session to access the logged-in user ID

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if required fields are set
    if (!isset($_POST['reported_user_id'], $_POST['action'], $_POST['reason'], $_SESSION['user_id'])) {
        die("Invalid form submission.");
    }

    // Get form data
    $reportedUserId = htmlspecialchars($_POST['reported_user_id']);
    $action = htmlspecialchars($_POST['action']);
    $reason = htmlspecialchars($_POST['reason']);
    $reportingUserId = $_SESSION['user_id']; // Logged-in user's ID

    // Establish database connection
    $conn = Database::getConnection();

    try {
        if ($action === 'report') {
            // Insert report into the database
            $stmt = $conn->prepare("INSERT INTO reports (reporting_user_id, reported_user_id, reason, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iis", $reportingUserId, $reportedUserId, $reason);
            $stmt->execute();
            echo "User has been reported.";
        } elseif ($action === 'block') {
            // Insert block into the database
            $stmt = $conn->prepare("INSERT INTO blocks (blocking_user_id, blocked_user_id, created_at) VALUES (?, ?, NOW())");
            $stmt->bind_param("ii", $reportingUserId, $reportedUserId);
            $stmt->execute();
            echo "User has been blocked.";
        } else {
            echo "Invalid action.";
        }
    } catch (Exception $e) {
        // Handle database errors
        echo "An error occurred: " . $e->getMessage();
    } finally {
        $stmt->close();
        $conn->close();
    }
} else {
    // Redirect to the main page if accessed without form submission
    header("Location: report_block.html");
    exit();
}
?>

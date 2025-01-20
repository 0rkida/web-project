<?php
require_once './src/db.php';
session_start(); // Start the session to access the logged-in user ID

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if required fields are set
    if (!isset($_POST['reported_user_id'], $_POST['action'], $_POST['reason'], $_SESSION['user_id'])) {
        die("Invalid form submission.");
    }

    // Get form data
    $reportedUserId = filter_var($_POST['reported_user_id'], FILTER_VALIDATE_INT);
    $action = filter_var($_POST['action'], FILTER_SANITIZE_STRING);
    $reason = isset($_POST['reason']) ? htmlspecialchars($_POST['reason']) : null;
    $reportingUserId = $_SESSION['user_id'];

    if(!$reportedUserId || !$action|| ($action === 'report' && empty($reason))){
        die("Invalid or missing from data.");
    }
    // Establish database connection
    $conn = Database::getConnection();

    try {
        if ($action === 'report') {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM reports WHERE reported_id = ? AND id = ?");
            $stmt->bind_param("ii", $reportedUserId, $reportingUserId);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                die("You have already reported this user.");
            }

            insertRecord($conn, 'reports', ['id', 'reported_id', 'reason', 'created_at'], [$reportingUserId, $reportedUserId, $reason, date('Y-m-d H:i:s')], 'iis');
            echo "User has been reported.";
        } else if ($action === 'block') {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM blocks WHERE blocking_user_id = ? AND blocked_user_id = ?");
            $stmt->bind_param("ii", $reportingUserId, $reportedUserId);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                die("You have already blocked this user.");
            }

            insertRecord($conn, 'blocks', ['blocking_user_id', 'blocked_user_id', 'created_at'], [$reportingUserId, $reportedUserId, date('Y-m-d H:i:s')], 'iis');
            echo "User has been blocked.";
        } else {
            echo "Invalid action.";
        }
    } catch (Exception $e) {
        // Handle database errors
        error_log("Error occurred: " . $e->getMessage());
        echo "An error occurred. Please try again later. ";
    } finally {
        $conn->close();
    }
} else {
    // Redirect to the main page if accessed without form submission
    header("Location: report_block.html");
    exit();
}
?>

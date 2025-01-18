<?php
require '../../db.php/';

if (isset($_GET['action'])) {
$action = $_GET['action'];
$conn = Database::getConnection();

if ($action == 'delete' && isset($_GET['report_id'])) {
$reportId = $_GET['report_id'];
$sql = "DELETE FROM reports WHERE id = '$reportId'";
mysqli_query($conn, $sql);
echo "Report deleted.";
} elseif ($action == 'block' && isset($_GET['user_id'])) {
$userId = $_GET['user_id'];
$sql = "INSERT INTO blocks (blocking_user_id, blocked_user_id, created_at) VALUES ('admin', '$userId', NOW())";
mysqli_query($conn, $sql);
echo "User blocked.";
}
}


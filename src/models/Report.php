<?php
// Get the JSON input data
$data = json_decode(file_get_contents('php://input'), true);

// Validate the data
if (isset($data['reportedUser']) && isset($data['reason'])) {
    $reportedUser = $data['reportedUser'];
    $reason = $data['reason'];
    $additionalInfo = isset($data['additionalInfo']) ? $data['additionalInfo'] : '';

    // Database connection (adjust as needed)
    $host = 'localhost';
    $db = 'your_database';
    $user = 'your_username';
    $password = 'your_password';

    $conn = new mysqli($host, $user, $password, $db);

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Insert the report into the database
    $stmt = $conn->prepare("INSERT INTO reports (reported_user, reason, additional_info) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $reportedUser, $reason, $additionalInfo);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
}
?>

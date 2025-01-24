<?php
// Database connection
$host = 'localhost';
$dbname = 'datting_app';
$username = 'root';
$password = '';
$conn = new mysqli($host, $dbname, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch all pending reports
function fetchReports($conn) {
    $sql = "SELECT id, reported_id, reason, additional_info, status FROM reports WHERE status = 'pending'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $reports = [];
        while ($row = $result->fetch_assoc()) {
            $reports[] = $row;
        }
        return $reports;
    } else {
        return [];
    }
}

// Handling actions (e.g., Warn, Block, Delete)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $report_id = $_POST['report_id'];

    if ($action === 'Warn') {
        $sql = "UPDATE reports SET status = 'reviewed' WHERE id = ?";
    } elseif ($action === 'Block') {
        $sql = "UPDATE reports SET status = 'reviewed' WHERE id = ?";
        // You might want to add logic to block the user in the users table here.
    } elseif ($action === 'Delete') {
        $sql = "DELETE FROM reports WHERE id = ?";
    }

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $report_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to refresh the page and reflect changes
    header('Location: admin-reports.php');
    exit();
}

// Fetch the reports from the database
$reports = fetchReports($conn);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reports</title>
    <link rel="stylesheet" href="admin-reports.css">
</head>
<body>
<section class="reports">
    <div class="top">
        <h2>Reported Users</h2>
    </div>
    <div class="reports-content">
        <table id="reports-table">
            <thead>
            <tr>
                <th>Report ID</th>
                <th>Reported User ID</th>
                <th>Reason</th>
                <th>Additional Info</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($reports as $report): ?>
                <tr>
                    <td><?php echo htmlspecialchars($report['id']); ?></td>
                    <td><?php echo htmlspecialchars($report['reported_id']); ?></td>
                    <td><?php echo htmlspecialchars($report['reason']); ?></td>
                    <td><?php echo htmlspecialchars($report['additional_info']); ?></td>
                    <td><?php echo htmlspecialchars($report['status']); ?></td>
                    <td>
                        <!-- Action Form for Warn -->
                        <form action="admin-reports.html" method="POST" style="display:inline;">
                            <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                            <button type="submit" name="action" value="Warn">Warn</button>
                        </form>
                        <!-- Action Form for Block -->
                        <form action="admin-reports.html" method="POST" style="display:inline;">
                            <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                            <button type="submit" name="action" value="Block">Block</button>
                        </form>
                        <!-- Action Form for Delete -->
                        <form action="admin-reports.html" method="POST" style="display:inline;">
                            <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                            <button type="submit" name="action" value="Delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
</body>
</html>

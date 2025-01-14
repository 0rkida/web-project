<?php
// Database connection (adjust parameters as needed)
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_database_username';
$password = 'your_database_password';
$conn = new mysqli($host, $username, $password, $dbname);

include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch all reports
function fetchReports($conn) {
    $sql = "SELECT report_id, reported_user, reason, reporter_user FROM reports WHERE status = 'pending'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $reports = [];
        while($row = $result->fetch_assoc()) {
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
    $reported_user = $_POST['reported_user'];

    if ($action === 'Warn') {
        $sql = "UPDATE reports SET status = 'warned' WHERE report_id = ?";
    } elseif ($action === 'Block') {
        $sql = "UPDATE reports SET status = 'blocked' WHERE report_id = ?";
    } elseif ($action === 'Delete') {
        $sql = "DELETE FROM reports WHERE report_id = ?";
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
    <link rel="stylesheet" href="styles.css">
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
                <th>Reported User</th>
                <th>Reason</th>
                <th>Reported By</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($reports as $report): ?>
                <tr>
                    <td><?php echo htmlspecialchars($report['reported_user']); ?></td>
                    <td><?php echo htmlspecialchars($report['reason']); ?></td>
                    <td><?php echo htmlspecialchars($report['reporter_user']); ?></td>
                    <td>
                        <!-- Action Form for Warn -->
                        <form action="report-management.php" method="POST" style="display:inline;">
                            <input type="hidden" name="report_id" value="<?php echo $report['report_id']; ?>">
                            <input type="hidden" name="reported_user" value="<?php echo $report['reported_user']; ?>">
                            <button type="submit" name="action" value="Warn">Warn</button>
                        </form>
                        <!-- Action Form for Block -->
                        <form action="report-management.php" method="POST" style="display:inline;">
                            <input type="hidden" name="report_id" value="<?php echo $report['report_id']; ?>">
                            <input type="hidden" name="reported_user" value="<?php echo $report['reported_user']; ?>">
                            <button type="submit" name="action" value="Block">Block</button>
                        </form>
                        <!-- Action Form for Delete -->
                        <form action="report-management.php" method="POST" style="display:inline;">
                            <input type="hidden" name="report_id" value="<?php echo $report['report_id']; ?>">
                            <input type="hidden" name="reported_user" value="<?php echo $report['reported_user']; ?>">
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
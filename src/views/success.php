<?php
session_start();  // Start the session


// Check if payment data is stored in the session
if (isset($_SESSION['payment'])) {
$payment = $_SESSION['payment'];
} else {
// If no payment data exists, handle the error, or redirect
echo "Payment information not found.";
exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
</head>
<body>
<div class="container">
    <h1>Payment Successful!</h1>
    <p>Thank you for your payment. Your transaction has been completed.</p>

    <h3>Payment Details:</h3>
    <ul>
        <li><strong>Payment ID:</strong> <?php echo htmlspecialchars($payment['id']); ?></li>
        <li><strong>Amount:</strong> €<?php echo htmlspecialchars($payment['amount']); ?></li>
        <li><strong>Status:</strong> <?php echo htmlspecialchars($payment['status']); ?></li>
        <li><strong>Transaction Date:</strong> <?php echo htmlspecialchars($payment['created_at']); ?></li>
    </ul>

    <a href="/profile.php" class="btn">Return to Profile</a>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
     <!-- Optional CSS -->
</head>
<body>
<div class="container">
    <h1>Payment Successful!</h1>
    <p>Thank you for your payment. Your transaction has been completed.</p>

    <h3>Payment Details:</h3>
    <ul>
        <li><strong>Payment ID:</strong> <?php echo htmlspecialchars($payment['id']); ?></li>
        <li><strong>Amount:</strong> €<?php echo htmlspecialchars($payment['amount']); ?></li>
        <li><strong>Status:</strong> <?php echo htmlspecialchars($payment['status']); ?></li>
        <li><strong>Transaction Date:</strong> <?php echo htmlspecialchars($payment['created_at']); ?></li>
    </ul>

    <a href="/profil" class="btn">Return to Profile</a> <!-- Example button to redirect the user back -->
</div>
</body>
</html>

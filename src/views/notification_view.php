<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
</head>
<body>

<?php if (!empty($notifications)): ?>
    <h1>Notifications</h1>
    <ul>
        <?php foreach ($notifications as $notification): ?>
            <li>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($notification['type']); ?></p>
                <p><strong>Message:</strong> <?php echo htmlspecialchars($notification['message']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($notification['created_at']); ?></p>
                <p><strong>Status:</strong> <?php echo $notification['is_read'] ? 'Read' : 'Unread'; ?></p>
            </li>
            <hr>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No notifications found.</p>
<?php endif; ?>

</body>
</html>

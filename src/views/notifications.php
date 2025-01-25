<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/notifications.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,800">
    <title>Notifications</title>
    <script src="/js/logout.js"></script>
</head>
<body>

<div class="notifications-container">
    <h2>Notifications</h2>
    <?php if (!empty($notifications)): ?>
        <?php foreach ($notifications as $notification): ?>
            <div class="notification-item">
            <span class="notification-type <?= htmlspecialchars($notification['type']) ?>">
                <?= ucfirst($notification['type']) ?>
            </span>
                <div class="notification-content">
                    <p class="notification-message"><?= htmlspecialchars($notification['message']) ?></p>
                    <span class="notification-meta"><?= timeAgo($notification['created_at']) ?></span>
                </div>
                <button class="close-btn">❤️ Mark as read</button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No notifications found.</p>
    <?php endif; ?>


    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const notificationItems = document.querySelectorAll(".notification-item");

        notificationItems.forEach((item) => {
            const button = item.querySelector(".close-btn");

            button.addEventListener("click", () => {
                item.classList.add("read");
                button.textContent = "❤️ Read";
                button.style.backgroundColor = "#f8d7da";
                button.style.color = "#c0392b";
                button.style.cursor = "not-allowed";
                button.disabled = true;
            });
        });
    });
</script>

</body>
</html>

<?php
// Function for time formatting (timeAgo)
function timeAgo($datetime): string
{
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) {
        return $diff . " seconds ago";
    } elseif ($diff < 3600) {
        return round($diff / 60) . " minutes ago";
    } elseif ($diff < 86400) {
        return round($diff / 3600) . " hours ago";
    } else {
        return round($diff / 86400) . " days ago";
    }
}
?>

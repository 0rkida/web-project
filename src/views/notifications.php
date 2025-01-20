<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,800">
    <title>Notifications</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f57760;
            margin: 0;
            padding: 20px;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .notifications-container {
            max-width: 800px;
            width: 100%;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h2 {
            font-size: 28px;
            color:#322525;
            margin-bottom: 20px;
            text-align: center;
        }
        .notification-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            background: rgba(251, 180, 183, 0.78);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        .notification-type {
            font-size: 14px;
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 16px;
            color: #fff;
        }
        .joined { background-color: #e63946; }
        .message { background-color: #f4a261; }
        .comment { background-color: #8e44ad; }
        .connect { background-color: #3498db; }
        .notification-content {
            flex-grow: 1;
            margin-left: 10px;
        }
        .notification-title {
            font-weight: bold;
            margin: 0;
            color: #264653;
        }
        .notification-message {
            margin: 5px 0;
            font-size: 14px;
            color: #6c757d;
        }
        .notification-meta {
            font-size: 12px;
            color: #555;
        }
        .close-btn {
            background-color: #f57760; /* Vibrant pink */
            border: none;
            color: white;
            font-size: 14px;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Montserrat', sans-serif;
            font-weight: bold;
        }

        .close-btn:hover {
            background-color: #e63946; /* Bold red on hover */
            transform: scale(1.05); /* Slight zoom effect */
            box-shadow: 0 4px 8px rgba(230, 57, 70, 0.4); /* Red shadow */
        }

        .close-btn:active {
            background-color: #c0392b; /* Deep red when clicked */
            transform: scale(0.95); /* Slight shrink on click */
            box-shadow: none; /* Remove shadow on click */
        }
    </style>
</head>
<body>

<div class="notifications-container">
    <h2>Notifications</h2>
    <?php if (!empty($notifications)): ?>
        <?php foreach ($notifications as $notification): ?>
            <div class="notification-item">
                <span class="notification-type <?= htmlspecialchars($notification['type']) ?>"><?= ucfirst(htmlspecialchars($notification['type'])) ?></span>
                <div class="notification-content">
                    <p class="notification-title"><?= htmlspecialchars($notification['title']) ?></p>
                    <p class="notification-message"><?= htmlspecialchars($notification['message']) ?></p>
                    <span class="notification-meta"><?= timeAgo($notification['created_at']) ?></span>
                </div>
                <button class="close-btn" data-id="<?= htmlspecialchars($notification['id']) ?>">❤️ Mark as read</button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No new notifications.</p>
    <?php endif; ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const notificationItems = document.querySelectorAll(".notification-item");

        notificationItems.forEach((item) => {
            const button = item.querySelector(".close-btn");

            button.addEventListener("click", () => {
                // Simulate marking as read (you can implement an AJAX call here)
                const notificationId = button.getAttribute("data-id");

                fetch(`/mark-as-read?id=${notificationId}`, { method: 'POST' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mark notification as read
                            item.classList.add("read");

                            // Disable the button
                            button.textContent = "❤️ Read";
                            button.style.backgroundColor = "#f8d7da";
                            button.style.color = "#c0392b";
                            button.style.cursor = "not-allowed";
                            button.disabled = true;
                        } else {
                            alert("Failed to mark notification as read.");
                        }
                    });
            });
        });
    });
</script>

</body>
</html>

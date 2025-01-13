class Notification {
    constructor(userId, message) {
        this.userId = userId; // ID of the user receiving the notification
        this.message = message; // Message to be displayed
        this.timestamp = new Date(); // Time the notification was created
    }

    // Method to display the notification on the webpage
    static displayNotification(userId, message) {
        const notificationContainer = document.getElementById('notification-container');
        const notification = new Notification(userId, message);
        const notificationElement = document.createElement('div');
        notificationElement.classList.add('notification');

        // Set the content of the notification
        notificationElement.innerHTML = `
            <p>${notification.message}</p>
            <span class="timestamp">${notification.timestamp.toLocaleString()}</span>
            <button class="close-btn">&times;</button>
        `;

        // Add the notification to the notification container
        notificationContainer.appendChild(notificationElement);

        // Add event listener for the close button
        const closeButton = notificationElement.querySelector('.close-btn');
        closeButton.addEventListener('click', () => {
            notificationElement.remove();
        });

        // Optional: Automatically remove notification after 5 seconds
        setTimeout(() => {
            notificationElement.remove();
        }, 5000);
    }
    // Example of marking a notification as read

    function
    markNotificationAsRead(notificationId) {
        fetch(`/path/to/markNotificationAsRead.php?notification_id=${notificationId}`, { method: 'POST' })
            .then(response => response.json())
            .then(data => console.log('Notification marked as read:', data))
            .catch(error => console.error('Error:', error));
    }

    deleteNotification(notificationId) {
        fetch(`/path/to/markNotificationAsRead.php?notification_id=${notificationId}&delete=true`, { method: 'POST' })
            .then(response => response.json())
            .then(data => console.log('Notification deleted:', data))
            .catch(error => console.error('Error:', error));
    }


}

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
        `;

        // Add the notification to the notification container
        notificationContainer.appendChild(notificationElement);

        // Optional: Automatically remove notification after 5 seconds
        setTimeout(() => {
            notificationElement.remove();
        }, 5000);
    }
}

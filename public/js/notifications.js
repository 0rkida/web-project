/* notifications.js */
function initNotifications() {
    const notificationBell = document.querySelector('#notification-bell');
    const notificationList = document.querySelector('#notification-list');

    notificationBell.addEventListener('click', () => {
        if (notificationList.classList.contains('hidden')) {
            notificationList.classList.remove('hidden');
        } else {
            notificationList.classList.add('hidden');
        }
    });

    // Mock notifications for demonstration purposes
    function loadNotifications() {
        const notifications = [
            'New message from Jane',
            'Your profile was liked!',
            'Admin approved your photo',
        ];

        notifications.forEach((notification) => {
            const listItem = document.createElement('li');
            listItem.textContent = notification;
            notificationList.appendChild(listItem);
        });
    }

    loadNotifications();
}

window.addEventListener('DOMContentLoaded', initNotifications);

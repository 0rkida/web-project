function initApp() {
    console.log('Initializing application...');

    // Initialize features
    if (typeof initChat === 'function') initChat();
    if (typeof initNotifications === 'function') initNotifications();
    if (typeof initValidation === 'function') initValidation();
}

window.addEventListener('DOMContentLoaded', initApp);

// Set the timeout duration (15 minutes in milliseconds)
const TIMEOUT_DURATION = 15* 60 * 1000;
// Function to handle logout
function logout() {
    fetch('/logout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ message: 'User inactive for 15 minutes' })
    }).then(response => {
        if (response.ok) {
            window.location.href = '/logout'; // Redirect to login page
        } else {
            console.error('Logout failed');
        }
    }).catch(error => {
        console.error('Error during logout:', error);
    });
}
// Function to reset the timer
function resetTimer() {
    clearTimeout(timer);
    timer = setTimeout(logout, TIMEOUT_DURATION);
}
// Initialize timer
let timer = setTimeout(logout, TIMEOUT_DURATION);

// Event listeners for user activity
['click', 'mousemove', 'keydown', 'scroll'].forEach(event => {
    window.addEventListener(event, resetTimer);
});

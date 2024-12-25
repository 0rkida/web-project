/* admin.js */
function manageUsers() {
    const deleteUserButtons = document.querySelectorAll('.delete-user');
    deleteUserButtons.forEach((button) => {
        button.addEventListener('click', () => {
            if (confirm('Are you sure you want to delete this user?')) {
                alert('User deleted successfully.');
            }
        });
    });
}
function loadAdminStats() {
    const statsSection = document.querySelector('#admin-stats');
    if (statsSection) {
        statsSection.innerHTML = '<p>Loading stats...</p>';
        setTimeout(() => {
            statsSection.innerHTML = '<p>New Users: 50<br>Reports: 3<br>Matches Made: 20</p>';
        }, 2000);
    }
}
window.addEventListener('DOMContentLoaded', () => {
    manageUsers();
    loadAdminStats();
});

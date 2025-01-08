document.querySelector('.dropdown-arrow').addEventListener('click', function() {
    const dropdownMenu = document.querySelector('.dropdown-menu');
    // Toggle the display property
    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
});

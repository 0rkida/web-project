// Function to preview image before upload
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profile_pic_preview');
            preview.src = e.target.result;
            preview.style.display = 'block';  // Show the preview image
        }
        reader.readAsDataURL(file);
    }
}

// Function to validate the image file type and size
function validateFile(file) {
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    const maxSize = 5 * 1024 * 1024;  // 5MB limit

    if (file && !allowedTypes.includes(file.type)) {
        alert('Please upload a valid image file (JPEG, PNG, GIF)');
        return false;
    }

    if (file && file.size > maxSize) {
        alert('File size exceeds the 5MB limit');
        return false;
    }

    return true;
}

// Handle profile picture upload form submission
document.getElementById('profile-form').addEventListener('submit', function(event) {
    const fileInput = document.getElementById('profile_picture');
    const file = fileInput.files[0];

    if (file && !validateFile(file)) {
        event.preventDefault();  // Prevent form submission if validation fails
    }
});

// Handle file input change event
document.getElementById('profile_picture').addEventListener('change', function(event) {
    previewImage(event);
});

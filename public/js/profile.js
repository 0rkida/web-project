<<<<<<< HEAD
// Fetch user profile data (this could come from an API or database)
const userProfile = {
    name: 'Anne Hathaway',
    bio: 'Lorem ipsum dolor sit amet consectetuer adipiscing.',
    photo: 'http://upload.wikimedia.org/wikipedia/commons/e/e1/Anne_Hathaway_Face.jpg'
};

// Elements on the profile page
const profileName = document.getElementById('user-name');
const profileBio = document.getElementById('profile-description').querySelector('p');
const profilePhoto = document.getElementById('profile-picture');

// Update profile info
function updateProfile() {
    profileName.textContent = userProfile.name;
    profileBio.textContent = userProfile.bio;
    profilePhoto.src = userProfile.photo;
}

// Edit profile functionality
const editProfileBtn = document.getElementById('edit-profile-btn');
const saveProfileBtn = document.getElementById('save-profile-btn');
const nameInput = document.getElementById('name-input');
const bioInput = document.getElementById('bio-input');
const photoInput = document.getElementById('photo-input');

// Enable editing when the user clicks the "Edit" button
editProfileBtn.addEventListener('click', () => {
    nameInput.value = userProfile.name;
    bioInput.value = userProfile.bio;
    photoInput.value = userProfile.photo;

    nameInput.disabled = false;
    bioInput.disabled = false;
    photoInput.disabled = false;

    editProfileBtn.style.display = 'none';
    saveProfileBtn.style.display = 'block';
});

// Save the updated profile
saveProfileBtn.addEventListener('click', () => {
    userProfile.name = nameInput.value;
    userProfile.bio = bioInput.value;
    userProfile.photo = photoInput.value;

    updateProfile();

    // Disable inputs after saving the changes
    nameInput.disabled = true;
    bioInput.disabled = true;
    photoInput.disabled = true;

    editProfileBtn.style.display = 'block';
    saveProfileBtn.style.display = 'none';
});

// Call updateProfile to display the initial data
document.addEventListener('DOMContentLoaded', updateProfile);
=======
document.querySelector('.dropdown-arrow').addEventListener('click', function() {
    const dropdownMenu = document.querySelector('.dropdown-menu');
    // Toggle the display property
    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
});
>>>>>>> e5090113bfa2fab8188931142ac9299df799258a

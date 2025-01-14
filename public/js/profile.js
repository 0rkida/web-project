

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

document.querySelector('.dropdown-arrow').addEventListener('click', function() {
    const dropdownMenu = document.querySelector('.dropdown-menu');
    // Toggle the display property
    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
});

document.addEventListener("DOMContentLoaded", async () => {
    const userId = 1; // Replace with the logged-in user's ID

    try {
        const response = await fetch(`http://localhost/dating_app/get_profile.php?user_id=${userId}`);
        const result = await response.json();

        if (result.status === "success") {
            const user = result.data;

            // Update profile details
            document.querySelector(".profile-name").textContent = user.username;
            document.querySelector(".location").textContent = `${user.age} • ${user.location}`;
            document.querySelector(".summary p").textContent = user.bio;

            // Update profile picture
            const profilePic = user.profilePic || "default-avatar.jpeg";
            document.querySelector(".large-profile-pic").src = `../assets/img/${profilePic}`;
            document.querySelector(".profile-pic").src = `../assets/img/${profilePic}`;
        } else {
            console.error("Error:", result.message);
        }
    } catch (error) {
        console.error("Error fetching user data:", error);
    }
});

document.addEventListener("DOMContentLoaded", async () => {
    const userId = 1; // Replace with the actual logged-in user's ID.

    try {
        const response = await fetch(`http://localhost/dating_app/get_profile.php?user_id=${userId}`);
        const result = await response.json();

        if (result.status === "success") {
            const user = result.data;

            // Populate the self-summary textarea with the user's current self-summary
            document.getElementById("self-summary").textContent = user.self_summary || '';  // Ensure it's not null
        } else {
            console.error("Error:", result.message);
        }
    } catch (error) {
        console.error("Error fetching user data:", error);
    }
});

document.getElementById('upload-input').addEventListener('change', function(event) {
    const fileInput = event.target;
    const form = document.getElementById('upload-form');

    // Submit the form when files are selected
    form.submit();
});
function saveChanges() {
    let hobby = document.getElementById("hobby").value;
    let ethnicity = document.getElementById("ethnicity").value;
    let profession = document.getElementById("profession").value;
    let goodAt = document.getElementById("good-at").value;
    let height = document.getElementById("height").value;

    // Here, you can send the data to the server or save it locally
    console.log("Changes Saved: ");
    console.log("Hobby:", hobby);
    console.log("Ethnicity:", ethnicity);
    console.log("Profession:", profession);
    console.log("Good At:", goodAt);
    console.log("Height:", height);
}


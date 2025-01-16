// Assume userProfile is populated with user data from the backend
let userProfile = {};

// Update profile info
function updateProfile() {
    profileName.textContent = userProfile.name || 'Name not available';
    profileBio.textContent = userProfile.bio || 'Bio not available';
    profilePhoto.src = userProfile.photo || 'default-avatar.jpeg';
}

// Edit profile functionality
editProfileBtn.addEventListener('click', () => {
    nameInput.value = userProfile.name || '';
    bioInput.value = userProfile.bio || '';
    photoInput.value = userProfile.photo || '';

    nameInput.disabled = false;
    bioInput.disabled = false;
    photoInput.disabled = false;

    editProfileBtn.style.display = 'none';
    saveProfileBtn.style.display = 'block';
});

saveProfileBtn.addEventListener('click', () => {
    userProfile.name = nameInput.value;
    userProfile.bio = bioInput.value;
    userProfile.photo = photoInput.value;

    updateProfile();

    nameInput.disabled = true;
    bioInput.disabled = true;
    photoInput.disabled = true;

    editProfileBtn.style.display = 'block';
    saveProfileBtn.style.display = 'none';

    // Send updated profile to the server
    fetch('/profile/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken // Include CSRF token if applicable
        },
        body: JSON.stringify(userProfile)
    }).then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                console.log('Profile updated successfully.');
            } else {
                console.error('Error:', result.message);
            }
        }).catch(error => {
        console.error('Error updating profile:', error);
    });
});

document.addEventListener('DOMContentLoaded', updateProfile);

document.querySelector('.dropdown-arrow').addEventListener('click', function() {
    const dropdownMenu = document.querySelector('.dropdown-menu');
    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
});

document.addEventListener("DOMContentLoaded", async () => {
    const userId = 1; // Replace with the logged-in user's ID

    try {
        const response = await fetch(`http://localhost/dating_app/get_profile.php?user_id=${userId}`);
        const result = await response.json();

        if (result.status === "success") {
            userProfile = result.data;
            updateProfile();
        } else {
            console.error("Error:", result.message);
        }
    } catch (error) {
        console.error("Error fetching user data:", error);
    }
});

document.getElementById('upload-input').addEventListener('change', function() {
    const form = document.getElementById('upload-form');
    form.submit();
});

function saveChanges() {
    let hobby = document.getElementById("hobby").value;
    let ethnicity = document.getElementById("ethnicity").value;
    let profession = document.getElementById("profession").value;
    let goodAt = document.getElementById("good-at").value;
    let height = document.getElementById("height").value;

    console.log("Changes Saved: ");
    console.log("Hobby:", hobby);
    console.log("Ethnicity:", ethnicity);
    console.log("Profession:", profession);
    console.log("Good At:", goodAt);
    console.log("Height:", height);

    // Send the data to the server
    fetch('/profile/saveChanges', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken // Include CSRF token if applicable
        },
        body: JSON.stringify({ hobby, ethnicity, profession, goodAt, height })
    }).then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                console.log('Changes saved successfully.');
            } else {
                console.error('Error:', result.message);
            }
        }).catch(error => {
        console.error('Error saving changes:', error);
    });
}

let inactivityTime = function () {
    let time;
    const logout = () => {
        alert("You have been logged out due to inactivity.");
        window.location.href = "logout.php";
    };

    const resetTimer = () => {
        clearTimeout(time);
        time = setTimeout(logout, 15 * 60 * 1000); // 15 minutes
    };

    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
};

inactivityTime();

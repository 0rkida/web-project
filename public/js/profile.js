// Existing profile.js code
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

// Combine multiple DOMContentLoaded event listeners
document.addEventListener("DOMContentLoaded", async () => {
    updateProfile();

    // Fetch user data for profile details
    const userId = 1; // Replace with the logged-in user's ID
    try {
        const response = await fetch(`http://localhost/dating_app/get_profile.php?user_id=${userId}`);
        const result = await response.json();

        if (result.status === "success") {
            const user = result.data;
            document.querySelector(".profile-name").textContent = user.username;
            document.querySelector(".location").textContent = `${user.age} • ${user.location}`;
            document.querySelector(".summary p").textContent = user.bio;
            const profilePic = user.profilePic || "default-avatar.jpeg";
            document.querySelector(".large-profile-pic").src = `../assets/img/${profilePic}`;
            document.querySelector(".profile-pic").src = `../assets/img/${profilePic}`;
            document.getElementById("self-summary").textContent = user.self_summary || '';
        } else {
            console.error("Error:", result.message);
        }
    } catch (error) {
        console.error("Error fetching user data:", error);
    }

    // Dropdown menu functionality
    document.querySelector('.dropdown-arrow').addEventListener('click', function() {
        const dropdownMenu = document.querySelector('.dropdown-menu');
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });

    // Upload form submission
    document.getElementById('upload-input').addEventListener('change', function(event) {
        const form = document.getElementById('upload-form');
        form.submit();
    });

    // Subscribe button functionality
    const subscribeButton = document.getElementById('subscribeBtntn');
    subscribeButton.addEventListener('click', async () => {
        try {
            const response = await fetch('/payment/initiate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    itemName: 'Premium Subscription',
                    itemPrice: 25.0,
                    currency: 'EUR',
                }),
            });

            const data = await response.json();

            if (data.clientSecret) {
                stripe.confirmCardPayment(data.clientSecret, {
                    payment_method: {
                        card: cardElement, // From Stripe.js card input
                        billing_details: {
                            name: document.getElementById('name').value,
                        },
                    },
                }).then((result) => {
                    if (result.error) {
                        console.error(result.error.message);
                    } else if (result.paymentIntent.status === 'succeeded') {
                        saveTransaction(result.paymentIntent);
                    }
                });
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Inactivity logout
    inactivityTime();
});

// New event listener for the "Update Info" button
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('updateInfoBtn').addEventListener('click', function() {
        alert('Update Info button clicked!');
        document.querySelector('form').submit(); // Submit the form
    });
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

let inactivityTime = function () {
    let time;
    const logout = () => {
        alert("You have been logged out due to inactivity.");
        window.location.href = "logout.php"; // Redirect to logout script
    };

    // Reset the timer when user interacts
    const resetTimer = () => {
        clearTimeout(time);
        time = setTimeout(logout, 15 * 60 * 1000); // 15 minutes
    };

    // Detect user actions
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
};



// Array of profiles
const profiles = [
    {
        name: 'Jane Doe',
        bio: 'Loves hiking, cooking, and exploring new places. Let’s connect!',
        photo: 'default-profile.jpg',
        notifications: []  // List of notifications for each user
    },
    {
        name: 'John Smith',
        bio: 'Avid traveler and photographer. Looking for my partner-in-crime!',
        photo: 'profile-john.jpg',
        notifications: []
    },
    {
        name: 'Emily Johnson',
        bio: 'Bookworm and coffee enthusiast. Let’s write our story together.',
        photo: 'profile-emily.jpg',
        notifications: []
    }
];

// Track current index of profiles
let currentIndex = 0;

// DOM elements
const profileCard = document.getElementById('profile-card');
const dislikeBtn = document.getElementById('dislike-btn');
const likeBtn = document.getElementById('like-btn');

// Update profile information
function updateProfile() {
    const profile = profiles[currentIndex];
    profileCard.querySelector('.profile-picture').src = profile.photo;
    profileCard.querySelector('.profile-name').textContent = profile.name;
    profileCard.querySelector('.profile-bio').textContent = profile.bio;
}

// Handle swipe actions (like or dislike)
function handleSwipe(isLiked) {
    const currentProfile = profiles[currentIndex];

    if (isLiked) {
        console.log(`You liked ${currentProfile.name}!`);

        // Add notification to the liked user's profile
        const likedUserIndex = (currentIndex + 1) % profiles.length;  // Get the next user (for simplicity)
        const likedUser = profiles[likedUserIndex];
        likedUser.notifications.push(`User ${currentProfile.name} liked your photo!`);

        // Display the notification for the liked user (alert)
        updateNotificationDisplay(likedUser.notifications);
    } else {
        console.log(`You disliked ${currentProfile.name}.`);
    }

    // Move to the next profile
    currentIndex = (currentIndex + 1) % profiles.length;
    updateProfile();
}

// Update the notification list
function updateNotificationDisplay(notifications) {
    const notificationList = document.getElementById('notification-list');
    notificationList.innerHTML = ''; // Clear existing notifications

    notifications.forEach(notification => {
        const li = document.createElement('li');
        li.textContent = notification;
        notificationList.appendChild(li);
    });
}

// Add event listeners for buttons
dislikeBtn.addEventListener('click', () => handleSwipe(false));
likeBtn.addEventListener('click', () => handleSwipe(true));

// Initial profile load
updateProfile();

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dating App - Find your Love</title>
    <script src="/js/logout.js"></script>
    <link rel="stylesheet" href="/css/home.css">
</head>
<body>
<header class="main-header">
    <div class="container">
        <h1>Welcome to Mish&Thu</h1>
        <p>Explore to find your perfect match!</p>
        <a href="/profil" class="btn profile-btn">My Profile</a>
    </div>
</header>

<div id="searchContainer">
    <form id="searchForm" method="GET" action="/search">
        <input type="text" name="name" placeholder="Search by name" required>
        <button type="submit">Search</button>
    </form>
</div>

<main class="swipe-container">
    <?php
    if (!empty($users)) {
        foreach ($users as $user) {
            $profile_picture_path = $user['picture_path'];
//            if (empty($user['picture_path'])) {
//                $profile_picture_path = '/assets/img/user-uploads/default-profile.jpg'; // Default profile picture
//            }
            echo '<div class="profile-card">';
            echo '<img src="' . htmlspecialchars($profile_picture_path) . '" height="300" width="300" class="profile-picture" alt="profile-pic"/>';
            echo '<h3 class="profile-name">' . htmlspecialchars($user['username']) . '</h3>';
            echo '</div>';
        }
    } else {
        echo 'No profiles found.';
    }
    ?>
    <div class="swipe-buttons">
        <button id="dislike-btn" class="btn dislike">&#10060; No</button>
        <button id="like-btn" class="btn like">&#10084; Yes</button>
    </div>
</main>

<footer class="main-footer">
    <div class="container">
        <p>&copy; 2025 Mish&Thu. All rights reserved.</p>
        <nav>
            <a href="MishEThu.html">About Us</a> |
            <a href="privacy.html">Privacy Policy</a> |
            <a href="contact.html">Contact</a>
        </nav>
    </div>
</footer>

<script>
    const profileCards = document.querySelectorAll('.profile-card');
    const dislikeBtn = document.getElementById('dislike-btn');
    const likeBtn = document.getElementById('like-btn');

    let currentIndex = 0;

    function updateProfile() {
        profileCards.forEach((card, index) => {
            card.style.display = (index === currentIndex) ? 'block' : 'none';
        });
    }

    function handleSwipe(isLiked) {
        if (isLiked) {
            console.log(`You liked ${profileCards[currentIndex].querySelector('.profile-name').textContent}!`);
        } else {
            console.log(`You disliked ${profileCards[currentIndex].querySelector('.profile-name').textContent}.`);
        }

        // Move to the next profile in the array
        currentIndex = (currentIndex + 1) % profileCards.length;
        updateProfile();
    }

    dislikeBtn.addEventListener('click', () => handleSwipe(false));  // Handle Dislike
    likeBtn.addEventListener('click', () => handleSwipe(true));      // Handle Like

    updateProfile();  // Initialize with the first profile
</script>
</body>
</html>

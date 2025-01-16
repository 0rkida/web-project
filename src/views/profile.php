<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/profile.css">
</head>
<body>
<div class="main-container">
    <!-- Left Sidebar -->
    <div class="sidebar left-sidebar">
        <div class="profile-section">
            <img src="../assets/img/default-avatar.jpeg" height="736" width="736" alt="Default Avatar" class="profile-pic">
            <div class="username-dropdown">
                <h3 class="username">
                    <?php echo $full_name; ?>
                    <span class="dropdown-arrow">▼</span>
                </h3>
                <ul class="dropdown-menu">
                    <li><a href="/profile/update">Edit Profile</a></li>
                    <li><a href="/account/settings">Account Settings</a></li>
                    <li><a href="/logout" class="logout-btn">Log Out</a></li>
                </ul>

            </div>
        </div>

        <nav>
            <ul class="menu">
                <li>
                    <a href="../chat.html">
                        <button class="menu-btn">Messages</button>
                    </a>
                </li>
                <li>
                    <a href="../notifications.html">
                        <button class="menu-btn">Notifications</button>
                    </a>
                </li>
                <li>
                    <a href="../matches.html">
                        <button class="menu-btn">Matchers</button>
                    </a>
                </li>
                <li><button class="menu-btn">Favorites</button></li>
            </ul>
        </nav>

        <div class="recently-visited">
            <h4>Recently Visited</h4>
            <div class="avatars">
                <img src="user1.jpg" alt="User 1">
                <img src="user2.jpg" alt="User 2">
                <img src="user3.jpg" alt="User 3">
            </div>
        </div>
    </div>

    <!-- Middle Section -->
    <div class="profile-container">
        <header class="profile-header">
            <div class="profile-info">
                <img src="../assets/img/default-avatar.jpeg" height="736" width="736" alt="Profile Picture" class="large-profile-pic">
                <div class="profile-text">
                    <h1 class="profile-name"><?php echo $full_name; ?></h1>
                    <span class="location"><?php echo $location; ?></span>
                </div>
            </div>
            <button class="update-btn">Update Info</button>
        </header>
        <div class="content">
            <section class="summary">
                <h2>My self-summary</h2>
                <p>
                    <?php echo $summary; ?>
                </p>
            </section>
            <section class="photos">
                <h2>Photos</h2>
                <div class="photo-grid">
                    <img src="photo1.jpg" alt="Photo 1">
                    <img src="photo2.jpg" alt="Photo 2">
                    <img src="photo3.jpg" alt="Photo 3">
                    <img src="photo4.jpg" alt="Photo 4">
                </div>
            </section>
            <section class="details">
                <h2>My Details</h2>
                <ul>
                    <li><?php echo $height; ?></li>
                    <li>Orientation: Straight</li>
                    <li>Diet: Vegetarian</li>
                    <li>Smokes: No</li>
                </ul>
            </section>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="sidebar right-sidebar">
        <img src="../assets/img/logo.png" height="300" width="300" alt="Logo" class="premium-image"/>
        <div class="premium-section">
            <h2 class="premium-title">You're Invisible</h2>
            <p>In order to increase your visibility and remove ads, go Premium!</p>
            <button class="premium-btn">Go Premium</button>
        </div>
    </div>

</div>
<script src="<?php echo dirname(__DIR__, 2) . '/profile.js'; ?>"></script>
</body>
</html>

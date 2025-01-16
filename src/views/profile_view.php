<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
</head>
<body>

<?php if (!empty($userProfile)): ?>
    <h1>Profile of <?php echo htmlspecialchars($userProfile['full_name'], ENT_QUOTES, 'UTF-8'); ?></h1>
    <p>Email: <?php echo htmlspecialchars($userProfile['email'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Age: <?php echo htmlspecialchars($userProfile['age'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Gender: <?php echo htmlspecialchars($userProfile['gender'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Location: <?php echo htmlspecialchars($userProfile['location'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Self Summary: <?php echo htmlspecialchars($userProfile['self_summary'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Hobbies: <?php echo htmlspecialchars($userProfile['hobby'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>What I'm Doing With Life: <?php echo htmlspecialchars($userProfile['doing_with_life'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Good At: <?php echo htmlspecialchars($userProfile['good_at'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Ethnicity: <?php echo htmlspecialchars($userProfile['ethnicity'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Height: <?php echo htmlspecialchars($userProfile['height'], ENT_QUOTES, 'UTF-8'); ?> meters</p>
    <p>Last Online: <?php echo htmlspecialchars($userProfile['last_online'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Created At: <?php echo htmlspecialchars($userProfile['created_at'], ENT_QUOTES, 'UTF-8'); ?></p>

    <!-- Profile Picture -->
    <img src="/assets/img/user-uploads/albums/<?php echo htmlspecialchars($userProfile['picture_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Picture" width="150">

    <!-- Update Form -->
    <form method="POST" action="/profile/update">
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($userProfile['full_name'], ENT_QUOTES, 'UTF-8'); ?>"><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userProfile['email'], ENT_QUOTES, 'UTF-8'); ?>"><br><br>

        <label for="age">Age:</label>
        <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($userProfile['age'], ENT_QUOTES, 'UTF-8'); ?>"><br><br>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender">
            <option value="male" <?php echo ($userProfile['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
            <option value="female" <?php echo ($userProfile['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
        </select><br><br>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($userProfile['location'], ENT_QUOTES, 'UTF-8'); ?>"><br><br>

        <label for="self_summary">Self Summary:</label>
        <textarea id="self_summary" name="self_summary"><?php echo htmlspecialchars($userProfile['self_summary'], ENT_QUOTES, 'UTF-8'); ?></textarea><br><br>

        <label for="hobby">Hobby:</label>
        <textarea id="hobby" name="hobby"><?php echo htmlspecialchars($userProfile['hobby'], ENT_QUOTES, 'UTF-8'); ?></textarea><br><br>

        <label for="doing_with_life">What are you doing with your life?</label>
        <textarea id="doing_with_life" name="doing_with_life"><?php echo htmlspecialchars($userProfile['doing_with_life'], ENT_QUOTES, 'UTF-8'); ?></textarea><br><br>

        <label for="good_at">Good At:</label>
        <textarea id="good_at" name="good_at"><?php echo htmlspecialchars($userProfile['good_at'], ENT_QUOTES, 'UTF-8'); ?></textarea><br><br>

        <label for="ethnicity">Ethnicity:</label>
        <input type="text" id="ethnicity" name="ethnicity" value="<?php echo htmlspecialchars($userProfile['ethnicity'], ENT_QUOTES, 'UTF-8'); ?>"><br><br>

        <label for="height">Height (in cm):</label>
        <input type="number" id="height" name="height" value="<?php echo htmlspecialchars($userProfile['height'], ENT_QUOTES, 'UTF-8'); ?>"><br><br>

        <button type="submit">Update Profile</button>
    </form>
<?php else: ?>
    <p>User profile not found.</p>
<?php endif; ?>

</body>
</html>

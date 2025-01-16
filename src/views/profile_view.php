<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
</head>
<body>

<?php if (!empty($userProfile)): ?>
<h1>Profile of <?php echo htmlspecialchars($userProfile['full_name']); ?></h1>
<p>Email: <?php echo htmlspecialchars($userProfile['email']); ?></p>
<p>Age: <?php echo htmlspecialchars($userProfile['age']); ?></p>
<p>Gender: <?php echo htmlspecialchars($userProfile['gender']); ?></p>
<p>Location: <?php echo htmlspecialchars($userProfile['location']); ?></p>
<p>Self Summary: <?php echo htmlspecialchars($userProfile['self_summary']); ?></p>
<p>Hobbies: <?php echo htmlspecialchars($userProfile['hobby']); ?></p>
<p>What I'm Doing With Life: <?php echo htmlspecialchars($userProfile['doing_with_life']); ?></p>
<p>Good At: <?php echo htmlspecialchars($userProfile['good_at']); ?></p>
<p>Ethnicity: <?php echo htmlspecialchars($userProfile['ethnicity']); ?></p>
<p>Height: <?php echo htmlspecialchars($userProfile['height']); ?> meters</p>
<p>Last Online: <?php echo htmlspecialchars($userProfile['last_online']); ?></p>
<p>Created At: <?php echo htmlspecialchars($userProfile['created_at']); ?></p>

<!-- Profile Picture -->
<img src="/assets/img/user-uploads/albums/<?php echo htmlspecialchars($userProfile['profile_picture']); ?>" alt="Profile Picture" width="150">

<!-- Update Form -->
<form method="POST" action="/profile/update">
    <label for="full_name">Full Name:</label>
    <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($userProfile['full_name']); ?>"><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userProfile['email']); ?>"><br><br>

    <label for="age">Age:</label>
    <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($userProfile['age']); ?>"><br><br>

    <label for="gender">Gender:</label>
    <select id="gender" name="gender">
        <option value="male" <?php echo ($userProfile['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
        <option value="female" <?php echo ($userProfile['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
    </select><br><br>

    <label for="location">Location:</label>
    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($userProfile['location']); ?>"><br><br>

    <label for="self_summary">Self Summary:</label>
    <textarea id="self_summary" name="self_summary"><?php echo htmlspecialchars($userProfile['self_summary']); ?></textarea><br><br>

    <label for="hobby">Hobby:</label>
    <textarea id="hobby" name="hobby"><?php echo htmlspecialchars($userProfile['hobby']); ?></textarea><br><br>

    <label for="doing_with_life">What are you doing with your life?</label>
    <textarea id="doing_with_life" name="doing_with_life"><?php echo htmlspecialchars($userProfile['doing_with_life']); ?></textarea><br><br>

    <label for="good_at">Good At:</label>
    <textarea id="good_at" name="good_at"><?php echo htmlspecialchars($userProfile['good_at']); ?></textarea><br><br>

    <label for="ethnicity">Ethnicity:</label>
    <input type="text" id="ethnicity" name="ethnicity" value="<?php echo htmlspecialchars($userProfile['ethnicity']); ?>"><br><br>

    <label for="height">Height (in cm):</label>
    <input type="number" id="height" name="height" value="<?php echo htmlspecialchars($userProfile['height']); ?>"><br><br>


    <button type="submit">Update Profile</button>
</form>

</body>
</html>

<?php endif?>
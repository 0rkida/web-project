<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Form</title>
    <script src="/js/logout.js"></script>
    <link rel="stylesheet" href="/css/editProfile.css">
</head>
<body>
<div class="profile-container">
    <h1>Profile Form</h1>
    <button type="button" id="updateInfoBtn" class="profile-btn">Update Info</button>

    <form action="/profil/update" method="POST" enctype="multipart/form-data">

        <div class="form-row">
            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" id="profile_picture" name="profile_picture">
            </div>

            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" value="<?= htmlspecialchars($data['age']) ?>">
            </div>

            <div class="form-group">
                <label for="gender">Gender:</label>
                <input type="text" id="gender" name="gender" value="<?= htmlspecialchars($data['gender']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" value="<?= htmlspecialchars($data['location']) ?>">
            </div>

            <div class="form-group">
                <label for="self_summary">Self Summary:</label>
                <textarea id="self_summary" name="self_summary"><?= htmlspecialchars($data['self_summary']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="hobby">Hobby:</label>
                <input type="text" id="hobby" name="hobby" value="<?= htmlspecialchars($data['hobby']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="doing_with_life">Doing with Life:</label>
                <input type="text" id="doing_with_life" name="doing_with_life" value="<?= htmlspecialchars($data['doing_with_life']) ?>">
            </div>

            <div class="form-group">
                <label for="good_at">Good at:</label>
                <input type="text" id="good_at" name="good_at" value="<?= htmlspecialchars($data['good_at']) ?>">
            </div>

            <div class="form-group">
                <label for="ethnicity">Ethnicity:</label>
                <input type="text" id="ethnicity" name="ethnicity" value="<?= htmlspecialchars($data['ethnicity']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="height">Height:</label>
                <input type="text" id="height" name="height" value="<?= htmlspecialchars($data['height']) ?>">
            </div>
        </div>

        <button type="submit">Update Profile</button>
    </form>
</div>
</body>
</html>

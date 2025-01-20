global$data; global$data; <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Form</title>
</head>
<body>
<h1>Profile Form</h1>
<form action="/profil/update" method="POST">
    <label for="profile_picture">Profile Picture:</label>
    <input type="text" id="profile_picture" name="profile_picture" value="<?= htmlspecialchars($data['profile_picture']) ?>">

    <label for="age">Age:</label>
    <input type="number" id="age" name="age" value="<?= htmlspecialchars($data['age']) ?>">

    <label for="gender">Gender:</label>
    <input type="text" id="gender" name="gender" value="<?= htmlspecialchars($data['gender']) ?>">

    <label for="location">Location:</label>
    <input type="text" id="location" name="location" value="<?= htmlspecialchars($data['location']) ?>">

    <label for="self_summary">Self Summary:</label>
    <textarea id="self_summary" name="self_summary"><?= htmlspecialchars($data['self_summary']) ?></textarea>

    <label for="hobby">Hobby:</label>
    <input type="text" id="hobby" name="hobby" value="<?= htmlspecialchars($data['hobby']) ?>">

    <label for="doing_with_life">Doing with Life:</label>
    <input type="text" id="doing_with_life" name="doing_with_life" value="<?= htmlspecialchars($data['doing_with_life']) ?>">

    <label for="good_at">Good at:</label>
    <input type="text" id="good_at" name="good_at" value="<?= htmlspecialchars($data['good_at']) ?>">

    <label for="ethnicity">Ethnicity:</label>
    <input type="text" id="ethnicity" name="ethnicity" value="<?= htmlspecialchars($data['ethnicity']) ?>">

    <label for="height">Height:</label>
    <input type="text" id="height" name="height" value="<?= htmlspecialchars($data['height']) ?>">

    <button type="submit">Update Profile</button>
</form>
<button type="button" id="updateInfoBtn" class="profile-btn">Update Info</button>
</body>
</html>

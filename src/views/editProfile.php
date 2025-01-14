<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Form</title>
</head>
<body>
<h1>Profile Form</h1>
<form action="/profil/update" method="post">
    <label for="profile_picture">Profile Picture Path:</label>
    <input type="text" id="profile_picture" name="profile_picture" value="<?=htmlspecialchars($data['profile_picture'])?>"><br><br>

    <label for="age">Age:</label>
    <input type="number" id="age" name="age" value="<?=htmlspecialchars($data['age'])?>" required><br><br>

    <label for="gender">Gender:</label>
    <select id="gender" name="gender" required>
        <option value="male" <?=($data['gender'] === "male" ? "selected" : "")?>>Male</option>
        <option value="female" <?=($data['gender'] === "female" ? "selected" : "")?>>Female</option>
    </select><br><br>

    <label for="location">Location:</label>
    <input type="text" id="location" name="location" value="<?=htmlspecialchars($data['location'])?>"><br><br>

    <label for="self_summary">Self Summary:</label><br>
    <textarea id="self_summary" name="self_summary"><?=htmlspecialchars($data['self_summary'])?></textarea><br><br>

    <label for="hobby">Hobbies:</label><br>
    <textarea id="hobby" name="hobby"><?=htmlspecialchars($data['hobby'])?></textarea><br><br>

    <label for="doing_with_life">Doing With Life:</label><br>
    <textarea id="doing_with_life" name="doing_with_life"><?=htmlspecialchars($data['doing_with_life'])?></textarea><br><br>

    <label for="good_at">Good At:</label><br>
    <textarea id="good_at" name="good_at"><?=htmlspecialchars($data['good_at'])?></textarea><br><br>

    <label for="ethnicity">Ethnicity:</label>
    <input type="text" id="ethnicity" name="ethnicity" value="<?=htmlspecialchars($data['ethnicity'])?>"><br><br>

    <label for="height">Height:</label>
    <input type="number" step="0.01" id="height" name="height" value="<?=htmlspecialchars($data['height'])?>"><br><br>

    <button type="submit">Submit</button>
</form>
</body>
</html>

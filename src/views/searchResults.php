<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>

<header class="main-header">
    <h1>Search Results</h1>
</header>

<main>
    <?php if (!empty($results)): ?>
        <ul>
            <?php foreach ($results as $user): ?>
                <li>
                    <?= htmlspecialchars($user['full_name']); ?> - <?= htmlspecialchars($user['email']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No users found matching your search.</p>
    <?php endif; ?>
</main>

<a href="/home" class="btn">Back to Home</a>

</body>
</html>

<h2>Your Matches</h2>
<ul>
    <?php if (isset($matches)) {
        foreach ($matches as $match): ?>
            <li>
                <img src="<?= htmlspecialchars($match['photo']) ?>" alt="<?= htmlspecialchars($match['name']) ?>" width="50">
                <p>Name: <?= htmlspecialchars($match['name']) ?></p>
                <p>Bio: <?= htmlspecialchars($match['bio']) ?></p>
            </li>
        <?php endforeach;
    } ?>
</ul>

<!DOCTYPE html>
<html>
<?php require __DIR__ . '/partials/head.php'; ?>
<body class="d-flex flex-column min-vh-100">
    <?php require __DIR__ . '/partials/content_navbar.php'; ?>

    <main class="flex-grow-1">
        <div class="mt-5 container">
            <h1><?= htmlspecialchars($unit['title']) ?></h1>
            <div class="markdown-body"><?= $unit['body'] ?></div>
            <hr>
            <h3>Sections</h3>
            <ul>
                <?php foreach ($sections as $section): ?>
                    <li>
                        <a href="/section?id=<?= $section['id'] ?>">
                            <?= htmlspecialchars($section['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>

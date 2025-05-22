<!DOCTYPE html>
<html>
<?php require __DIR__ . '/partials/head.php'; ?>
<body class="d-flex flex-column min-vh-100">
    <?php require __DIR__ . '/partials/content_navbar.php'; ?>

    <main class="flex-grow-1">
        <div class="mt-5 container">
            <h1><?= htmlspecialchars($section['title']) ?></h1>
            <div class="markdown-body"><?= $section['body'] ?></div>
            <hr>
            <h3>Lessons</h3>
            <ul>
                <?php foreach ($lessons as $lesson): ?>
                    <li>
                        <a href="/lesson?id=<?= $lesson['id'] ?>">
                            <?= htmlspecialchars($lesson['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>

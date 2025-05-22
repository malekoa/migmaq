<!DOCTYPE html>
<html>
<?php require __DIR__ . '/partials/head.php'; ?>
<body class="d-flex flex-column min-vh-100">
    <?php require __DIR__ . '/partials/content_navbar.php'; ?>

    <main class="flex-grow-1">
        <div class="mt-5 container">
            <h1><?= htmlspecialchars($lesson['title']) ?></h1>
            <div class="markdown-body"><?= $lesson['body'] ?></div>
        </div>
    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>

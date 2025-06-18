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
        <?php if ($adjacent['prev'] || $adjacent['next']): ?>
            <div class="mt-5 text-center container">
                <div class="d-flex justify-content-between">
                    <div>
                        <?php if ($adjacent['prev']): ?>
                            <a class="btn-outline-primary btn" href="/lesson?id=<?= $adjacent['prev']['id'] ?>">
                                ← Previous: <?= htmlspecialchars($adjacent['prev']['title']) ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div>
                        <?php if ($adjacent['next']): ?>
                            <a class="btn-outline-primary btn" href="/lesson?id=<?= $adjacent['next']['id'] ?>">
                                Next: <?= htmlspecialchars($adjacent['next']['title']) ?> →
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>

</html>
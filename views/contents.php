<!DOCTYPE html>
<html>
<?php require __DIR__ . '/partials/head.php'; ?>

<body class="d-flex flex-column min-vh-100">
    <?php require __DIR__ . '/partials/content_navbar.php'; ?>

    <main class="flex-grow-1">
        <div class="mt-5 container">
            <h1 class="mb-4">📖 Table of Contents</h1>

            <?php foreach ($units as $unit): ?>
                <div class="mb-5">
                    <!-- Unit Title (clickable) -->
                    <h3>
                        <a href="/unit?id=<?= $unit['id'] ?>" class="text-decoration-none">
                            <?= htmlspecialchars($unit['title']) ?>
                        </a>
                    </h3>
                    <div class="ms-3">
                        <?php foreach ($unit['sections'] as $section): ?>
                            <!-- Section Title (clickable) -->
                            <h5>
                                <a href="/section?id=<?= $section['id'] ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($section['title']) ?>
                                </a>
                            </h5>
                            <ul class="ms-4">
                                <?php foreach ($section['lessons'] as $lesson): ?>
                                    <!-- Lesson Title (clickable) -->
                                    <li>
                                        <a href="/lesson?id=<?= $lesson['id'] ?>">
                                            <?= htmlspecialchars($lesson['title']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>

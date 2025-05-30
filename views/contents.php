<!DOCTYPE html>
<html>
<?php require __DIR__ . '/partials/head.php'; ?>

<body class="d-flex flex-column min-vh-100">
    <?php require __DIR__ . '/partials/content_navbar.php'; ?>

    <main class="flex-grow-1">
        <div class="mt-5 container">
            <h1 class="mb-4">📖 Table of Contents</h1>

            <ol class="list-unstyled">
                <?php foreach ($units as $uIndex => $unit): ?>
                    <li class="mb-3">
                        <strong>
                            <?= ($uIndex + 1) ?>. 
                            <a href="/unit?id=<?= $unit['id'] ?>" class="text-dark">
                                <?= htmlspecialchars($unit['title']) ?>
                            </a>
                        </strong>

                        <?php if (!empty($unit['sections'])): ?>
                            <ol class="ms-4 mt-2 list-unstyled">
                                <?php foreach ($unit['sections'] as $sIndex => $section): ?>
                                    <li class="mb-1">
                                        <?= ($uIndex + 1) . '.' . ($sIndex + 1) ?>.
                                        <a href="/section?id=<?= $section['id'] ?>" class="text-dark">
                                            <?= htmlspecialchars($section['title']) ?>
                                        </a>

                                        <?php if (!empty($section['lessons'])): ?>
                                            <ol class="ms-4 mt-1 list-unstyled">
                                                <?php foreach ($section['lessons'] as $lIndex => $lesson): ?>
                                                    <li>
                                                        <?= ($uIndex + 1) . '.' . ($sIndex + 1) . '.' . ($lIndex + 1) ?>.
                                                        <a href="/lesson?id=<?= $lesson['id'] ?>" class="text-dark">
                                                            <?= htmlspecialchars($lesson['title']) ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ol>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>

<!DOCTYPE html>
<html>
<?php require __DIR__ . '/partials/head.php'; ?>

<body class="d-flex flex-column min-vh-100">
    <?php require __DIR__ . '/partials/content_navbar.php'; ?>

    <main class="flex-grow-1">
        <div class="mt-5 container">
            <h1 class="mb-4">📖 Table of Contents</h1>

            <div class="row g-4">
                <?php foreach ($units as $unit): ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="shadow-sm h-100 card">
                            <div class="card-body">
                                <h4 class="mb-3 card-title">
                                    <a href="/unit?id=<?= $unit['id'] ?>" class="">
                                        <?= htmlspecialchars($unit['title']) ?>
                                    </a>
                                </h4>

                                <?php foreach ($unit['sections'] as $section): ?>
                                    <div class="mb-3 ps-2 border-start">
                                        <div class="mb-1 text-secondary fw-semibold">
                                            <i class="me-1 bi bi-folder"></i>
                                            <a href="/section?id=<?= $section['id'] ?>" class="text-decoration-none">
                                                <?= htmlspecialchars($section['title']) ?>
                                            </a>
                                        </div>
                                        <ul class="ps-3 border-start list-unstyled">
                                            <?php foreach ($section['lessons'] as $lesson): ?>
                                                <li>
                                                    <i class="me-1 text-muted bi bi-file-text"></i>
                                                    <a href="/lesson?id=<?= $lesson['id'] ?>" class="text-decoration-none">
                                                        <?= htmlspecialchars($lesson['title']) ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>

</html>
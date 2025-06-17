<!DOCTYPE html>
<html>
<?php require __DIR__ . '/partials/head.php'; ?>

<style>
    a:hover {
        text-decoration: underline !important;
    }

    .card:hover {
        transform: translateY(-2px);
        transition: transform 0.2s;
    }
</style>

<body class="d-flex flex-column min-vh-100">
    <?php require __DIR__ . '/partials/content_navbar.php'; ?>

    <main class="flex-grow-1">
        <div class="mt-5 container">
            <h1 class="mb-4 display-5">📗 <?= htmlspecialchars($section['title']) ?></h1>

            <div class="shadow-sm mb-5 card">
                <div class="card-body">
                    <div class="markdown-body">
                        <?= $section['body'] ?>
                    </div>
                </div>
            </div>

            <h3 class="mb-4">📙 Lessons</h3>

            <?php if (!empty($lessons)): ?>
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <?php foreach ($lessons as $index => $lesson): ?>
                        <div class="col">
                            <div class="shadow-sm h-100 card">
                                <div class="card-body">
                                    <div class="position-relative">
                                        <h5 class="mb-0 card-title">
                                            <?= ($index + 1) ?>.
                                            <?= htmlspecialchars($lesson['title']) ?>
                                        </h5>
                                        <a href="/lesson?id=<?= $lesson['id'] ?>" class="stretched-link"></a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">No lessons available for this section.</p>
            <?php endif; ?>
        </div>
    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>

</html>
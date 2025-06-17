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
            <h1 class="mb-4 display-5">📖 Table of Contents</h1>

            <?php foreach ($units as $uIndex => $unit): ?>
                <div class="mb-5">
                    <h3 class="mb-4 fw-bold">
                        📘 <?= ($uIndex + 1) ?>.
                        <a href="/unit?id=<?= $unit['id'] ?>" class="text-dark text-primary text-decoration-none">
                            <?= htmlspecialchars($unit['title']) ?>
                        </a>
                    </h3>

                    <?php if (!empty($unit['sections'])): ?>
                        <div class="row row-cols-1 row-cols-md-2 g-4">
                            <?php foreach ($unit['sections'] as $sIndex => $section): ?>
                                <div class="col">
                                    <div class="position-relative shadow-sm h-100 card">
                                        <div class="card-body">
                                            <h5 class="card-title fw-semibold">
                                                📗 <?= ($uIndex + 1) . '.' . ($sIndex + 1) ?>.
                                                <?= htmlspecialchars($section['title']) ?>
                                            </h5>

                                            <!-- Invisible stretched link to the unit page -->
                                            <a href="/section?id=<?= $section['id'] ?>" class="stretched-link"></a>


                                            <?php if (!empty($section['lessons'])): ?>
                                                <ul class="position-relative ms-2 mt-3 list-unstyled" style="z-index: 1;">
                                                    <?php foreach ($section['lessons'] as $lIndex => $lesson): ?>
                                                        <li class="mb-1">
                                                            📙 <?= ($uIndex + 1) . '.' . ($sIndex + 1) . '.' . ($lIndex + 1) ?>.
                                                            <a href="/lesson?id=<?= $lesson['id'] ?>" class="position-relative text-secondary text-decoration-none" style="z-index: 3;">
                                                                <?= htmlspecialchars($lesson['title']) ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </main>



    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>

</html>
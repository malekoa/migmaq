<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>

<nav class="bg-body-tertiary border-bottom navbar navbar-expand-lg">
    <div class="d-flex align-items-center justify-content-between container-fluid">

        <!-- Left: Back to Units + hierarchy -->
        <div class="d-flex align-items-center gap-3 py-2">
            <a href="/dashboard/unit-editor" class="btn-outline-secondary btn btn-sm">
                ← Back to Units
            </a>

            <!-- Contextual Hierarchy -->
            <div>
                <?php if (str_starts_with($currentPath, '/dashboard/unit-editor')): ?>
                    <strong>Viewing all Units</strong>
                <?php elseif (str_starts_with($currentPath, '/dashboard/section-editor') && isset($unit)): ?>
                    <span class="text-muted">Unit:</span>
                    <a href="/dashboard/section-editor?unitId=<?= $unit['id'] ?>">
                        <?= htmlspecialchars($unit['title']) ?>
                    </a>
                    <span class="mx-2">→</span>
                    <strong>Sections</strong>
                <?php elseif (str_starts_with($currentPath, '/dashboard/lesson-editor') && isset($unit, $section)): ?>
                    <span class="text-muted">Unit:</span>
                    <a href="/dashboard/section-editor?unitId=<?= $unit['id'] ?>">
                        <?= htmlspecialchars($unit['title']) ?>
                    </a>
                    <span class="mx-2">→</span>
                    <span class="text-muted">Section:</span>
                    <a href="/dashboard/lesson-editor?unitId=<?= $unit['id'] ?>&sectionId=<?= $section['id'] ?>">
                        <?= htmlspecialchars($section['title']) ?>
                    </a>
                    <span class="mx-2">→</span>
                    <strong>Lessons</strong>
                <?php else: ?>
                    <strong>Dashboard</strong>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: User info and logout -->
        <div class="d-flex align-items-center">
            <span class="navbar-text">Signed in as: <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
            <span class="me-3"></span>
            <a class="btn-success btn" href="/logout">Log Out</a>
        </div>
    </div>
</nav>

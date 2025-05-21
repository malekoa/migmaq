<?php if (!isset($sections)) $sections = []; ?>
<!DOCTYPE html>
<html>
<!-- HTML Head -->
<?php require __DIR__ . '/partials/head.php'; ?>
<body>
    <!-- Dashboard Navbar -->
    <?php require __DIR__ . '/partials/dashboard_navbar.php'; ?>
    <!-- Toast -->
    <?php require __DIR__ . '/partials/toast.php'; ?>
    <!-- Section Modal -->
    <?php require __DIR__ . '/partials/section_modal.php'; ?>

    <div class="mt-5 container">
        <h2>Sections for Unit: <?= htmlspecialchars($unit['title']) ?></h2>
        <?php if (empty($sections)): ?>
            <p>No sections yet.</p>
        <?php else: ?>
            <div id="sectionList" class="list-group">
                <?php foreach ($sections as $section): ?>
                    <?php
                    $statusClass = $section['status'] === 'published' ? 'success' : 'secondary';
                    $statusLabel = ucfirst($section['status']);
                    ?>
                    <div class="list-group-item mb-2 p-0 card" data-id="<?= htmlspecialchars($section['id']) ?>">
                        <div class="d-flex align-items-center justify-content-between card-body">
                            <div class="d-flex align-items-center gap-2">
                                <!-- Use an icon for the drag handle -->
                                <i class="bi bi-grip-vertical drag-handle" style="cursor: move;"></i>
                                <strong><?= htmlspecialchars($section['title']) ?></strong>
                                <span class="badge bg-<?= $statusClass ?>"><?= $statusLabel ?></span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="/dashboard/lesson-editor?unitId=<?= htmlspecialchars($unit['id']) ?>&sectionId=<?= htmlspecialchars($section['id']) ?>" class="btn-outline-secondary btn btn-sm">
                                    <i class="bi bi-journals"></i> Open Lessons
                                </a>
                                <button class="btn-outline-primary btn btn-sm edit-btn"
                                        data-id="<?= htmlspecialchars($section['id']) ?>"
                                        data-title="<?= htmlspecialchars($section['title'], ENT_QUOTES) ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#sectionModal">
                                    <i class="bi bi-pencil-square"></i> Edit Section
                                </button>
                                <form action="/section/delete" method="POST" class="d-inline">
                                    <input type="hidden" name="sectionId" value="<?= htmlspecialchars($section['id']) ?>">
                                    <input type="hidden" name="unitId" value="<?= htmlspecialchars($unit['id']) ?>">
                                    <button type="submit" class="btn-outline-danger btn btn-sm" onclick="return confirm('Delete this section?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <button type="button" class="mt-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#sectionModal">
            <i class="bi bi-plus-lg"></i> New Section
        </button>
    </div>

    <!-- Initialize SortableJS for sections -->
    <script>
        new Sortable(document.getElementById('sectionList'), {
            handle: '.drag-handle',  // allow drag only on this element
            animation: 150,
            onEnd: function () {
                // Collect the new order from the DOM
                const order = [...document.querySelectorAll('#sectionList .list-group-item')].map((el, idx) => ({
                    id: el.dataset.id,
                    position: idx
                }));
                // Send the updated order to the backend
                fetch('/section/update-order', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(order)
                }).then(res => {
                    if (!res.ok) console.error('Failed to update section order');
                });
            }
        });
    </script>
</body>
</html>

<?php if (!isset($units)) {
    $units = [];
} ?>

<!DOCTYPE html>
<html>

<!-- HTML Head -->
<?php require __DIR__ . '/partials/head.php'; ?>

<body>
    <!-- Dashboard Navbar -->
    <?php require __DIR__ . '/partials/dashboard_navbar.php'; ?>

    <!-- Toast -->
    <?php require __DIR__ . '/partials/toast.php'; ?>

    <!-- Unit Modal -->
    <?php require __DIR__ . '/partials/unit_modal.php'; ?>

    <!-- Unit List -->
    <div class="mt-5 container">
        <h2>All Units</h2>
        <?php if (empty($units)): ?>
            <p>No units added yet.</p>
        <?php else: ?>
            <div id="unitList" class="list-group">
                <?php foreach ($units as $unit): ?>
                    <?php
                    $statusClass = $unit['status'] === 'published' ? 'success' : 'secondary';
                    $statusLabel = ucfirst($unit['status']);
                    ?>
                    <div class="list-group-item mb-2 p-0 card" data-id="<?= htmlspecialchars($unit['id']) ?>">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi-grid-3x3-gap-fill text-black-50 bi drag-handle" style="cursor: move;"></i>
                                    <strong><?= htmlspecialchars($unit['title']) ?></strong>
                                    <span class="badge bg-<?= $statusClass ?>"><?= $statusLabel ?></span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn-outline-secondary btn btn-sm">
                                        <i class="bi bi-folder2-open"></i>
                                        Open Unit
                                    </button>
                                    <button class="btn-outline-primary btn btn-sm edit-btn"
                                        data-id="<?= htmlspecialchars($unit['id']) ?>"
                                        data-title="<?= htmlspecialchars($unit['title'], ENT_QUOTES) ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                        <i class="bi bi-pen"></i> Edit Unit Page
                                    </button>
                                    <form action="/unit/delete" method="POST" class="d-inline">
                                        <input type="hidden" name="unitId" value="<?= htmlspecialchars($unit['id']) ?>">
                                        <button type="submit" class="btn-outline-danger btn btn-sm" onclick="return confirm('Delete this unit?')">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Accordion for sections -->
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="bi bi-plus-lg"></i> New Unit
        </button>
    </div>

    <script>
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const unitId = btn.dataset.id;
                const unitTitle = btn.dataset.title;

                // Set form fields
                document.getElementById('unitId').value = unitId;
                document.getElementById('unitTitle').value = unitTitle;

                // Fetch body content via AJAX
                const res = await fetch(`/unit/fetch?id=${unitId}`);
                const data = await res.json();
                if (lessonEditor) {
                    lessonEditor.setContents(data.body || '');
                }
                document.getElementById('unitStatus').value = data.status || 'draft';
            });
        });
    </script>

    <script>
        new Sortable(document.getElementById('unitList'), {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function() {
                const order = [...document.querySelectorAll('#unitList .list-group-item')].map((el, idx) => ({
                    id: el.dataset.id,
                    position: idx
                }));

                fetch('/unit/update-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(order)
                }).then(res => {
                    if (!res.ok) console.error('Failed to update unit order');
                });
            }
        });
    </script>

</body>

</html>
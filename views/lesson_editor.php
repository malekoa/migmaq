<?php if (!isset($lessons)) $lessons = []; ?>

<!DOCTYPE html>
<html>

<!-- HTML Head -->
<?php require __DIR__ . '/partials/head.php'; ?>

<body>
    <!-- Navbar -->
    <?php require __DIR__ . '/partials/dashboard_navbar.php'; ?>

    <!-- Toast -->
    <?php require __DIR__ . '/partials/toast.php'; ?>

    <!-- Modal -->
    <?php require __DIR__ . '/partials/lesson_modal.php'; ?>

    <div class="mt-5 container">
        <h2>📝 Lessons for Section: <?= htmlspecialchars($section['title']) ?></h2>

        <?php if (empty($lessons)): ?>
            <p>No lessons in this section yet.</p>
        <?php else: ?>
            <div id="lessonList" class="list-group">
                <?php foreach ($lessons as $lesson): ?>
                    <?php
                    $statusClass = $lesson['status'] === 'published' ? 'success' : 'secondary';
                    $statusLabel = ucfirst($lesson['status']);
                    ?>
                    <div class="list-group-item mb-2 p-0 card" data-id="<?= htmlspecialchars($lesson['id']) ?>">
                        <div class="d-flex align-items-center justify-content-between card-body">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-grip-vertical drag-handle" style="cursor: move;"></i>
                                <strong><?= htmlspecialchars($lesson['title']) ?></strong>
                                <span class="badge bg-<?= $statusClass ?>"><?= $statusLabel ?></span>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn-outline-primary btn btn-sm edit-btn"
                                    data-id="<?= htmlspecialchars($lesson['id']) ?>"
                                    data-title="<?= htmlspecialchars($lesson['title'], ENT_QUOTES) ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#lessonModal">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <form action="/lesson/delete" method="POST" class="d-inline">
                                    <input type="hidden" name="lessonId" value="<?= htmlspecialchars($lesson['id']) ?>">
                                    <input type="hidden" name="unitId" value="<?= htmlspecialchars($section['unit_id']) ?>">
                                    <input type="hidden" name="sectionId" value="<?= htmlspecialchars($section['id']) ?>">
                                    <button type="submit" class="btn-outline-danger btn btn-sm"
                                        onclick="return confirm('Delete this lesson?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <button type="button" class="mt-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#lessonModal">
            <i class="bi bi-plus-lg"></i> New Lesson
        </button>
    </div>

    <script>
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const lessonId = btn.dataset.id;
                const lessonTitle = btn.dataset.title;

                document.getElementById('lessonId').value = lessonId;
                document.getElementById('lessonTitle').value = lessonTitle;

                const res = await fetch(`/lesson/fetch?id=${lessonId}`);
                const data = await res.json();
                if (lessonEditor) {
                    lessonEditor.setContents(data.body || '');
                }
                document.getElementById('lessonStatus').value = data.status || 'draft';
            });
        });
    </script>
    <script>
        new Sortable(document.getElementById('lessonList'), {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function() {
                const order = [...document.querySelectorAll('#lessonList .list-group-item')].map((el, idx) => ({
                    id: el.dataset.id,
                    position: idx
                }));

                fetch('/lesson/update-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(order)
                }).then(res => {
                    if (!res.ok) console.error('Failed to update lesson order');
                });
            }
        });
    </script>

</body>

</html>
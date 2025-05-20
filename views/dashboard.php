<?php if (!isset($units)) {
    $units = [];
} ?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="/src/suneditor.min.css">
    <script src="/src/suneditor.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
</head>

<body>
    <nav class="bg-body-tertiary navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Learn Mi'gmaq <i class="bi-chevron-compact-right bi"></i> Lesson Editor <i class="bi-chevron-compact-right bi"></i> Units</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="me-auto mb-2 mb-lg-0 navbar-nav">
                </ul>
                <div class="d-flex">
                    <span class="navbar-text">Signed in as: <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
                    <span class="me-3"></span>
                    <a class="btn-success btn" href="/logout">Log Out</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Toast -->
    <div class="top-0 position-fixed p-3 translate-middle-x start-50" style="z-index: 1100">
        <div id="toastMessage" class="shadow border-0 toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastBody">
                    <!-- Message will be injected here -->
                </div>
                <button type="button" class="m-auto me-2 btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal modal-xl fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Unit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/unit/save" method="POST" id="saveForm">
                        <label for="unitTitle" class="form-label">Unit Title:</label>
                        <input type="text" name="unitTitle" id="unitTitle" class="form-control"><br>
                        <input type="hidden" name="unitId" id="unitId">
                        <label for="unitBody" class="form-label">Unit Body:</label>
                        <!-- this textarea will get overwritten with the editor HTML -->
                        <textarea id="sample" name="unitBody" style="width:100%"></textarea>
                        <label for="unitStatus" class="form-label">Status:</label>
                        <select class="form-select" name="unitStatus" id="unitStatus">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <!-- this button submits the form -->
                    <button type="submit" form="saveForm" class="btn btn-primary">
                        Save changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let lessonEditor;
        window.addEventListener('DOMContentLoaded', () => {
            if (!lessonEditor) {
                lessonEditor = SUNEDITOR.create('sample', {
                    height: '300px',
                    buttonList: [
                        ['undo', 'redo'],
                        ['formatBlock'],
                        ['bold', 'underline', 'italic'],
                        ['list', 'align', 'horizontalRule'],
                        ['link', 'image', 'audio', 'table'],
                        ['codeView']
                    ],
                    // === audio upload config ===
                    audioFileInput: true, // show a file-picker in the audio dialog
                    audioUrlInput: false, // hide the URL‐input field
                    audioUploadUrl: '/audio/upload', // endpoint that handles the upload
                    audioUploadHeader: {}, // any custom headers, if needed
                    // audioMultipleFile: false,      // default = false
                    // audioUploadSizeLimit: 10485760, // e.g. 10MB limit
                    // optional: default table settings
                    tableDefaultWidth: '100%', // when you insert a table, width="100%"
                    tableDefaultStyle: 'border:1px solid #ccc;',
                    tableMaxWidth: null, // no max width
                    tableHeaderEnabled: true // allow header row checkbox
                });

            }
        });

        document.getElementById('saveForm')
            .addEventListener('submit', e => {
                // getContents(false) returns the full HTML
                document.getElementById('sample').value = lessonEditor.getContents(false);
                // now the POST will include the HTML in `unitBody`
            });
    </script>

    <!-- Units -->
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
                    <div class="list-group-item mb-2 p-0 card" data-id="<?= $unit['id'] ?>">
                        <div class="d-flex align-items-center justify-content-between card-body">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi-grid-3x3-gap-fill text-black-50 bi drag-handle" style="cursor: move;"></i>
                                <strong><?= htmlspecialchars($unit['title']) ?></strong>
                                <span class="badge bg-<?= $statusClass ?>"><?= $statusLabel ?></span>
                            </div>
                            <div>
                                <button class="me-2 btn-outline-primary btn btn-sm edit-btn"
                                    data-id="<?= $unit['id'] ?>"
                                    data-title="<?= htmlspecialchars($unit['title'], ENT_QUOTES) ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    <i class="bi bi-pen"></i> Edit
                                </button>
                                <form action="/unit/delete" method="POST" class="d-inline">
                                    <input type="hidden" name="unitId" value="<?= $unit['id'] ?>">
                                    <button type="submit" class="btn-outline-danger btn btn-sm" onclick="return confirm('Delete this unit?')">
                                        <i class="bi bi-trash3"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="bi bi-plus-lg"></i> New Unit
        </button>
    </div>


    <!-- Show toast if status in url -->
    <?php if (isset($_GET['status'])): ?>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const toastEl = document.getElementById('toastMessage');
                const toastBody = document.getElementById('toastBody');
                const toast = new bootstrap.Toast(toastEl, {
                    delay: 3000
                });

                <?php if ($_GET['status'] === 'success'): ?>
                    toastBody.textContent = 'Unit saved successfully!';
                    toastEl.classList.remove('bg-danger', 'text-white');
                    toastEl.classList.add('bg-white', 'text-dark');
                <?php elseif ($_GET['status'] === 'deleted'): ?>
                    toastBody.textContent = 'Unit deleted successfully!';
                    toastEl.classList.remove('bg-danger', 'text-white');
                    toastEl.classList.add('bg-white', 'text-dark');
                <?php else: ?>
                    toastBody.textContent = <?= json_encode('Error: ' . ($_GET['msg'] ?? 'Unknown error')) ?>;
                    toastEl.classList.remove('bg-white', 'text-dark');
                    toastEl.classList.add('bg-danger', 'text-white');
                <?php endif; ?>

                toast.show();
            });
        </script>
    <?php endif; ?>

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
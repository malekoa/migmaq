<?php // views/partials/section_modal.php 
?>
<div class="modal modal-xl fade" id="sectionModal" tabindex="-1" aria-labelledby="sectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="sectionModalLabel">Create Section</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/section/save" method="POST" id="sectionSaveForm">
                    <label for="sectionTitle" class="form-label">Section Title:</label>
                    <input type="text" name="sectionTitle" id="sectionTitle" class="form-control"><br>

                    <input type="hidden" name="sectionId" id="sectionId">
                    <input type="hidden" name="unitId" value="<?= htmlspecialchars($unit['id']) ?>">

                    <label for="sectionBody" class="form-label">Section Body:</label>
                    <textarea id="sectionEditor" name="sectionBody" style="width:100%"></textarea>

                    <label for="sectionStatus" class="form-label">Status:</label>
                    <select class="form-select" name="sectionStatus" id="sectionStatus">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                    <?= csrf_input() ?>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="sectionSaveForm" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    let sectionEditor;
    window.addEventListener('DOMContentLoaded', () => {
        if (!sectionEditor) {
            sectionEditor = SUNEDITOR.create('sectionEditor', {
                height: '300px',
                buttonList: [
                    ['undo', 'redo'],
                    ['formatBlock'],
                    ['bold', 'underline', 'italic'],
                    ['list', 'align', 'horizontalRule'],
                    ['link', 'image', 'audio', 'table'],
                    ['codeView']
                ],
                audioFileInput: true,
                audioUrlInput: false,
                audioUploadUrl: '/audio/upload',
                tableDefaultWidth: '100%',
                tableDefaultStyle: 'border:1px solid #ccc;',
                tableHeaderEnabled: true
            });
        }
    });

    document.getElementById('sectionSaveForm')
        .addEventListener('submit', e => {
            document.getElementById('sectionEditor').value = sectionEditor.getContents(false);
        });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Fill form fields when clicking an edit button
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const title = button.dataset.title;
                const body = button.dataset.body;
                const status = button.dataset.status;

                document.getElementById('sectionId').value = id;
                document.getElementById('sectionTitle').value = title;
                document.getElementById('sectionStatus').value = status;

                // Set editor content
                if (sectionEditor) {
                    sectionEditor.setContents(body || '');
                }

                // Update modal title
                document.getElementById('sectionModalLabel').textContent = 'Edit Section';
            });
        });

        // Reset form when opening for new section
        const sectionModal = document.getElementById('sectionModal');
        sectionModal.addEventListener('show.bs.modal', event => {
            const trigger = event.relatedTarget;
            if (!trigger.classList.contains('edit-btn')) {
                document.getElementById('sectionSaveForm').reset();
                document.getElementById('sectionId').value = '';
                document.getElementById('sectionModalLabel').textContent = 'Create Section';
                if (sectionEditor) {
                    sectionEditor.setContents('');
                }
            }
        });
    });
</script>
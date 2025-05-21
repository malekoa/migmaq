<?php // views/partials/section_modal.php ?>
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

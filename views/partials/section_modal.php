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
                <form action="/section/save" method="POST" id="saveSectionForm">
                    <input type="hidden" name="sectionId" id="sectionId">
                    <input type="hidden" name="unitId" id="sectionUnitId">

                    <label for="sectionTitle" class="form-label">Section Title:</label>
                    <input type="text" name="sectionTitle" id="sectionTitle" class="form-control"><br>

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
                <button type="submit" form="saveSectionForm" class="btn btn-primary">Save Section</button>
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
                audioUploadUrl: '/audio/upload'
            });
        }

        // Set the unit ID into the form when the button is clicked
        document.querySelectorAll('.open-section-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                const unitId = btn.getAttribute('data-unit-id');
                document.getElementById('sectionUnitId').value = unitId;
                sectionEditor.setContents('');
                document.getElementById('sectionTitle').value = '';
                document.getElementById('sectionStatus').value = 'draft';
                document.getElementById('sectionId').value = '';
            });
        });

        document.querySelectorAll('.edit-section-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const sectionId = btn.dataset.id;

                const res = await fetch(`/section/fetch?id=${sectionId}`);
                const section = await res.json();

                document.getElementById('sectionId').value = section.id;
                document.getElementById('sectionTitle').value = section.title;
                document.getElementById('sectionStatus').value = section.status;
                document.getElementById('sectionUnitId').value = section.unit_id; // ✅ set this!
                sectionEditor.setContents(section.body || '');
            });
        });


        // Fill body HTML before submit
        document.getElementById('saveSectionForm').addEventListener('submit', () => {
            document.getElementById('sectionEditor').value = sectionEditor.getContents(false);
        });
    });
</script>
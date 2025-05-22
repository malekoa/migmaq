<?php // views/partials/toast.php 
?>
<div class="top-0 position-fixed p-3 translate-middle-x start-50" style="z-index: 1100">
    <div id="toastMessage" class="shadow border-0 toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastBody"></div>
            <button type="button" class="m-auto me-2 btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
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
            <?php elseif ($_GET['status'] === 'section_deleted'): ?>
                toastBody.textContent = 'Section deleted successfully!';
                toastEl.classList.remove('bg-danger', 'text-white');
                toastEl.classList.add('bg-white', 'text-dark');
            <?php elseif ($_GET['status'] === 'section_saved'): ?>
                toastBody.textContent = 'Section saved successfully!';
                toastEl.classList.remove('bg-danger', 'text-white');
                toastEl.classList.add('bg-white', 'text-dark');
            <?php elseif ($_GET['status'] === 'lesson_saved'): ?>
                toastBody.textContent = 'Lesson saved successfully!';
                toastEl.classList.remove('bg-danger', 'text-white');
                toastEl.classList.add('bg-white', 'text-dark');
            <?php elseif ($_GET['status'] === 'lesson_deleted'): ?>
                toastBody.textContent = 'Lesson deleted successfully!';
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
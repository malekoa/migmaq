<!DOCTYPE html>
<html>
<?php require __DIR__ . '/partials/head.php'; ?>

<body>
    <?php require __DIR__ . '/partials/dashboard_navbar.php'; ?>

    <div class="mt-5 container">
        <h2>Manage Users</h2>
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <form action="/user/update" method="POST">
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td>
                                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="form-control form-control-sm" required>
                            </td>
                            <td>
                                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control form-control-sm" required>
                            </td>
                            <td>
                                <select name="role" class="form-select-sm form-select">
                                    <option value="contributor" <?= $user['role'] === 'contributor' ? 'selected' : '' ?>>Contributor</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="userId" value="<?= $user['id'] ?>">

                                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-floppy"></i> Save</button>

                                <button type="button"
                                    class="btn-outline-secondary btn btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#passwordModal"
                                    data-user-id="<?= $user['id'] ?>"
                                    data-username="<?= htmlspecialchars($user['username']) ?>">
                                    <i class="bi bi-lock"></i> Password
                                </button>

                                <form action="/user/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
                                    <input type="hidden" name="userId" value="<?= $user['id'] ?>">
                                    <button type="submit" class="btn-outline-danger btn btn-sm"><i class="bi bi-trash3"></i> Delete</button>
                                </form>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button class="mt-3 btn btn-success" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="bi bi-plus-lg"></i> New User
        </button>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="/user/change-password" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password for <span id="pwModalUsername"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="userId" id="pwModalUserId">
                    <label>New Password:</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create New User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="/user/create" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Username:</label>
                    <input type="text" name="username" class="form-control" required>

                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" required>

                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" required minlength="6">

                    <label>Role:</label>
                    <select name="role" class="form-select">
                        <option value="contributor">Contributor</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const passwordModal = document.getElementById('passwordModal');
            passwordModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-user-id');
                const username = button.getAttribute('data-username');
                passwordModal.querySelector('#pwModalUserId').value = userId;
                passwordModal.querySelector('#pwModalUsername').textContent = username;
            });
        });
    </script>
    <!-- Toast for Manage Users -->
    <div class="top-0 position-fixed p-3 translate-middle-x start-50" style="z-index: 1100">
        <div id="toastMessage" class="shadow border-0 toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastBody"></div>
                <button type="button" class="m-auto me-2 btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['status'])): ?>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const toastEl = document.getElementById('toastMessage');
                const toastBody = document.getElementById('toastBody');
                const toast = new bootstrap.Toast(toastEl, {
                    delay: 3000
                });

                <?php if ($_GET['status'] === 'updated'): ?>
                    toastBody.textContent = 'User updated successfully!';
                    toastEl.classList.remove('bg-danger', 'text-white');
                    toastEl.classList.add('bg-white', 'text-dark');
                <?php elseif ($_GET['status'] === 'deleted'): ?>
                    toastBody.textContent = 'User deleted successfully!';
                    toastEl.classList.remove('bg-danger', 'text-white');
                    toastEl.classList.add('bg-white', 'text-dark');
                <?php elseif ($_GET['status'] === 'password_changed'): ?>
                    toastBody.textContent = 'Password updated successfully!';
                    toastEl.classList.remove('bg-danger', 'text-white');
                    toastEl.classList.add('bg-white', 'text-dark');
                <?php elseif ($_GET['status'] === 'created'): ?>
                    toastBody.textContent = 'New user created!';
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


</body>

</html>
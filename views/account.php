<!DOCTYPE html>
<html>
<?php require __DIR__ . '/partials/head.php'; ?>
<body>
<?php require __DIR__ . '/partials/dashboard_navbar.php'; ?>

<div class="mt-5 container">
    <h2>👤 Manage Account</h2>

    <?php if (!empty($_SESSION['account_errors'])): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($_SESSION['account_errors'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; unset($_SESSION['account_errors']); ?>
            </ul>
        </div>
    <?php elseif (!empty($_SESSION['account_success'])): ?>
        <div class="alert alert-success">
            Password updated successfully!
        </div>
        <?php unset($_SESSION['account_success']); ?>
    <?php endif; ?>

    <form method="POST" action="/user/self-password" class="shadow-sm p-4 card" style="max-width: 500px;">
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" class="form-control" name="current_password" id="current_password" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control" name="new_password" id="new_password" required minlength="6">
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" name="confirm_password" id="confirm_password" required minlength="6">
        </div>
        <button type="submit" class="btn btn-primary">Change Password</button>
    </form>
</div>
</body>
</html>

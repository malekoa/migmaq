<!DOCTYPE html>
<html>
<?php require __DIR__ . '/partials/head.php'; ?>
<body>
<?php require __DIR__ . '/partials/dashboard_navbar.php'; ?>

<div class="mt-5 container">
    <h2>Dashboard</h2>
    <ul class="list-group">
        <li class="list-group-item">
            <a href="/dashboard/unit-editor">📚 Unit Editor</a>
        </li>
        <?php if (isAdmin()): ?>
            <li class="list-group-item">
                <a href="/dashboard/manage-users">👥 Manage Users</a>
            </li>
        <?php endif; ?>
    </ul>
</div>
</body>
</html>

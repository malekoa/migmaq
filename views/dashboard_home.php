<!DOCTYPE html>
<html>
<?php require __DIR__ . '/partials/head.php'; ?>

<body>
    <?php require __DIR__ . '/partials/dashboard_navbar.php'; ?>

    <div class="mt-5 container">
        <h2>🏠 Dashboard Home</h2>
        <ul class="list-group">
            <li class="list-group-item">
                <a href="/dashboard/unit-editor">📚 Unit Editor</a>
            </li>
            <li class="list-group-item">
                <a href="/dashboard/account">👤 Manage Account</a>
            </li>
            <?php if (isAdmin()): ?>
                <li class="list-group-item">
                    <a href="/dashboard/manage-users">⚙️ Admin Settings</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</body>

</html>
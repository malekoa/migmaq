<?php $title = 'Reset Password'; ?>
<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/partials/head.php'; ?>

<body class="d-flex align-items-center justify-content-center bg-light min-vh-100">
    <div class="container">
        <div class="justify-content-center row">
            <div class="shadow-sm p-4 card col-md-6 col-lg-5">
                <h1 class="mb-4 text-center">Reset Password</h1>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (empty($errors) || $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                    <form method="POST" action="/reset-password">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <?= csrf_input() ?>
                        <button class="w-100 btn btn-primary" type="submit">Reset Password</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>
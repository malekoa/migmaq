<?php $title = 'Forgot Password'; ?>
<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/partials/head.php'; ?>

<body class="d-flex align-items-center justify-content-center bg-light min-vh-100">
    <div class="container">
        <div class="justify-content-center row">
            <div class="shadow-sm p-4 card col-md-6 col-lg-5">
                <h1 class="mb-4 text-center">Forgot Password</h1>

                <?php if ($success): ?>
                    <div class="text-center alert alert-success">
                        A password reset link has been sent to your email.
                    </div>
                <?php elseif (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/forgot-password">
                    <div class="mb-3">
                        <label for="email" class="form-label">Your Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <?= csrf_input() ?>
                    <button class="w-100 btn btn-primary" type="submit">Send Reset Link</button>
                </form>

                <div class="mt-3 text-center">
                    <a href="/login" class="btn-outline-secondary btn">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
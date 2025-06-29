<?php $title = 'Login'; ?>
<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/partials/head.php'; ?>

<body class="d-flex align-items-center justify-content-center bg-light min-vh-100">

    <div class="container">
        <div class="justify-content-center row">
            <div class="col-md-6 col-lg-5">
                <div class="shadow-sm p-4 card">
                    <h1 class="mb-4 text-center">Sign In</h1>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="/login" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                required
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                required>
                        </div>
                        <?= csrf_input() ?>
                        <button type="submit" class="w-100 btn btn-primary">Log In</button>
                    </form>

                    <p class="mt-3 text-center small">
                        Don't have an account?
                        <a href="/register">Register here</a>.
                    </p>
                    <p class="mt-2 text-center small">
                        <a href="/forgot-password">Forgot your password?</a>
                    </p>

                </div>
            </div>
        </div>
    </div>

</body>

</html>
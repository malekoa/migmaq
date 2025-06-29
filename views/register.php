<?php $title = 'Register'; ?>
<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/partials/head.php'; ?>

<body class="d-flex align-items-center justify-content-center bg-light min-vh-100">

    <div class="container">
        <div class="justify-content-center row">
            <div class="col-md-6 col-lg-5">
                <div class="shadow-sm p-4 card">
                    <h1 class="mb-4 text-center">Register</h1>

                    <?php if ($success): ?>
                        <div class="text-center alert alert-success">
                            Registration successful! <a href="/login">Login here</a>.
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

                    <?php if (!$success): ?>
                        <form action="/register" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="username"
                                    name="username"
                                    required
                                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
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
                            <button type="submit" class="w-100 btn btn-primary">Register</button>

                            <p class="mt-3 text-center small">
                                Already have an account?
                                <a href="/login">Login here</a>.
                            </p>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
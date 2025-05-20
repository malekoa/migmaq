<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>

    <?php if ($success): ?>
        <p style="color:green;">Registration successful! <a href="/login">Login here</a>.</p>
    <?php elseif (!empty($errors)): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!$success): ?>
        <form action="/register" method="POST">
            <label>Username:
                <input type="text" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </label><br><br>
            <label>Email:
                <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </label><br><br>
            <label>Password:
                <input type="password" name="password" required>
            </label><br><br>
            <button type="submit">Register</button>
            <span>
                or
                <a href="/login">login</a>
            </span>
        </form>
    <?php endif; ?>
</body>
</html>

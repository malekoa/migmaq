<?php

class UserController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index(): void
    {
        if (!isAdmin()) {
            http_response_code(403);
            exit("Access denied");
        }

        $stmt = $this->pdo->query("SELECT id, username, email, role FROM users ORDER BY id ASC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo = $this->pdo;
        $allSettings = getAllSettings($this->pdo);

        require __DIR__ . '/../views/manage_users.php';
    }

    public function updateSetting(): void
    {
        verify_csrf_token_or_die();

        if (!isAdmin()) {
            http_response_code(403);
            exit("Access denied");
        }

        $key = $_POST['key'] ?? null;
        $value = $_POST['value'] ?? null;

        if ($key === null || $value === null) {
            http_response_code(400);
            exit("Missing key or value");
        }

        setSetting($this->pdo, $key, $value);
        header("Location: /dashboard/manage-users?status=setting_updated");
        exit();
    }


    public function updateRole(): void
    {
        verify_csrf_token_or_die();

        if (!isAdmin()) {
            http_response_code(403);
            exit("Access denied");
        }

        $userId = $_POST['userId'] ?? null;
        $role = $_POST['role'] ?? null;

        if ($userId && in_array($role, ['admin', 'contributor'])) {
            $stmt = $this->pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
            $stmt->execute([$role, $userId]);
            header("Location: /dashboard/manage-users?status=role_updated");
            exit;
        }

        http_response_code(400);
        exit("Invalid input");
    }

    public function changePassword(): void
    {
        verify_csrf_token_or_die();

        if (!isAdmin()) {
            http_response_code(403);
            exit("Access denied");
        }

        $userId = $_POST['userId'] ?? null;
        $password = $_POST['password'] ?? null;

        if ($userId && $password && strlen($password) >= 6) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed, $userId]);
            header("Location: /dashboard/manage-users?status=password_changed");
            exit;
        }

        http_response_code(400);
        exit("Invalid input");
    }

    public function createUser(): void
    {
        verify_csrf_token_or_die();

        if (!isAdmin()) {
            http_response_code(403);
            exit("Access denied");
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'contributor';

        if (!$username || !$email || !$password || strlen($password) < 6) {
            http_response_code(400);
            exit("Invalid input");
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashed, $role]);

        header("Location: /dashboard/manage-users?status=user_created");
        exit;
    }

    public function updateUser(): void
    {
        verify_csrf_token_or_die();

        if (!isAdmin()) {
            http_response_code(403);
            exit("Access denied");
        }

        $id = $_POST['userId'] ?? null;
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? null;

        if ($id && $username && $email && in_array($role, ['admin', 'contributor'])) {
            $stmt = $this->pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
            $stmt->execute([$username, $email, $role, $id]);
            header("Location: /dashboard/manage-users?status=updated");
            exit;
        }

        http_response_code(400);
        exit("Invalid input");
    }

    public function deleteUser(): void
    {
        verify_csrf_token_or_die();

        if (!isAdmin()) {
            http_response_code(403);
            exit("Access denied");
        }

        $id = $_POST['userId'] ?? null;
        if ($id) {
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: /dashboard/manage-users?status=deleted");
            exit;
        }

        http_response_code(400);
        exit("Missing ID");
    }

    public function toggleRegistration(): void
    {
        verify_csrf_token_or_die();

        if (!isAdmin()) {
            http_response_code(403);
            exit("Access denied");
        }

        $enabled = $_POST['enabled'] ?? '0';
        setSetting($this->pdo, 'registration_enabled', $enabled === '1' ? '1' : '0');
        header("Location: /dashboard/manage-users?status=registration_updated");
        exit;
    }

    public function showAccount(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }

        $userId = $_SESSION['user_id'];
        $stmt = $this->pdo->prepare("SELECT username, email FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            http_response_code(404);
            exit("User not found");
        }

        require __DIR__ . '/../views/account.php';
    }

    public function changeOwnPassword(): void
    {
        verify_csrf_token_or_die();

        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            exit("Unauthorized");
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $userId = $_SESSION['user_id'];

        $errors = [];

        if (!$currentPassword || !$newPassword || !$confirmPassword) {
            $errors[] = "All fields are required.";
        }

        if ($newPassword !== $confirmPassword) {
            $errors[] = "New passwords do not match.";
        }

        if (strlen($newPassword) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        }

        $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $hash = $stmt->fetchColumn();

        if (!$hash || !password_verify($currentPassword, $hash)) {
            $errors[] = "Current password is incorrect.";
        }

        if ($errors) {
            $_SESSION['account_errors'] = $errors;
            header("Location: /dashboard/account");
            exit;
        }

        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$newHash, $userId]);

        $_SESSION['account_success'] = true;
        header("Location: /dashboard/account");
        exit;
    }
}

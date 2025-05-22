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

        require __DIR__ . '/../views/manage_users.php';
    }

    public function updateRole(): void
    {
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
}

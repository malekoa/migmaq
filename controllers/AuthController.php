<?php
class AuthController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function showLogin()
    {
        $errors = [];
        require __DIR__ . '/../views/login.php';
    }

    public function login()
    {
        $errors = [];

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $errors[] = "Email and password are required.";
        } else {
            try {
                $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    header("Location: /dashboard");
                    exit();
                } else {
                    $errors[] = "Invalid email or password.";
                }
            } catch (PDOException $e) {
                $errors[] = "Login error: " . $e->getMessage();
            }
        }

        require __DIR__ . '/../views/login.php';
    }

    public function showRegister()
    {
        $errors = [];
        $success = isset($_GET['success']);

        if (getSetting($this->pdo, 'registration_enabled', '1') !== '1') {
            $errors[] = "Registration has been disabled by the administrator. Please contact them to create an account.";
            require __DIR__ . '/../views/register.php';
            return;
        }

        require __DIR__ . '/../views/register.php';
    }


    public function register()
    {
        $errors = [];
        $success = false;

        // ✅ FIRST: Check if registration is disabled
        if (getSetting($this->pdo, 'registration_enabled', '1') !== '1') {
            $errors[] = "Registration has been disabled by the administrator. Please contact them to create an account.";
            require __DIR__ . '/../views/register.php';
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$username || !$email || !$password) {
            $errors[] = "All fields are required.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        }

        if (empty($errors)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            try {
                $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
                $userCount = $stmt->fetchColumn();
                $role = $userCount == 0 ? 'admin' : 'contributor';

                $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $email, $hashedPassword, $role]);

                header("Location: /register?success=1");
                exit();
            } catch (PDOException $e) {
                if (str_contains($e->getMessage(), 'UNIQUE')) {
                    $errors[] = "Username or email already exists.";
                } else {
                    $errors[] = "Registration failed: " . $e->getMessage();
                }
            }
        }

        require __DIR__ . '/../views/register.php';
    }


    public function logout()
    {
        session_destroy();
        header("Location: /login");
        exit();
    }
}

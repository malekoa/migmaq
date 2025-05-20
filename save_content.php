<?php
session_start();
// optional: protect this endpoint
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// path to your SQLite file
$dbFile = __DIR__ . '/units.db';

try {
    // 1) open (or create) the DB
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2) ensure the table exists
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS units (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            title       TEXT    NOT NULL,
            body        TEXT    NOT NULL,
            created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // 3) only handle POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // grab & validate
        $title = trim($_POST['unitTitle'] ?? '');
        $body  = $_POST['unitBody'] ?? '';
        $unitId = $_POST['unitId'] ?? null;

        if ($title === '') {
            throw new Exception('Unit title cannot be empty');
        }

        // 4) insert or update
        if ($unitId) {
            // Update existing unit
            $stmt = $pdo->prepare('
                UPDATE units
                SET title = :title, body = :body
                WHERE id = :id
            ');
            $stmt->bindValue(':id', $unitId, PDO::PARAM_INT);
        } else {
            // Insert new unit
            $stmt = $pdo->prepare('
                INSERT INTO units (title, body)
                VALUES (:title, :body)
            ');
        }

        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':body',  $body,  PDO::PARAM_STR);
        $stmt->execute();

        // 5) redirect on success
        header('Location: dashboard.php?status=success');
        exit;
    }

    // invalid method
    throw new Exception('Invalid request method');
} catch (Exception $e) {
    // log & redirect on error
    error_log($e->getMessage());
    header('Location: dashboard.php?status=error&msg=' . urlencode($e->getMessage()));
    exit;
}

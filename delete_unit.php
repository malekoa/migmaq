<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $unitId = $_POST['unitId'] ?? null;
    if ($unitId) {
        $dbFile = __DIR__ . '/units.db';
        $pdo = new PDO('sqlite:' . $dbFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("DELETE FROM units WHERE id = ?");
        $stmt->execute([$unitId]);
    }
}

header("Location: dashboard.php?status=success");
exit();

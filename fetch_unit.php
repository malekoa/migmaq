<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    exit('Missing ID');
}

$dbFile = __DIR__ . '/units.db';
$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->prepare("SELECT body, status FROM units WHERE id = ?");
$stmt->execute([$_GET['id']]);
$unit = $stmt->fetch(PDO::FETCH_ASSOC);

if ($unit) {
    echo json_encode($unit);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}

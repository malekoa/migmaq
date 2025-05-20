<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    http_response_code(400);
    exit('Invalid input');
}

$dbFile = __DIR__ . '/units.db';
$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->prepare("UPDATE units SET position = :position WHERE id = :id");

foreach ($data as $item) {
    $stmt->execute([
        ':id' => $item['id'],
        ':position' => $item['position']
    ]);
}

echo json_encode(['status' => 'success']);

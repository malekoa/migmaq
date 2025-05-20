<?php
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit('Missing id');
}

$id = (int)$_GET['id'];
$db = new SQLite3(__DIR__ . '/audios.db');

$stmt = $db->prepare('SELECT mime, data FROM audios WHERE id = :id');
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$result = $stmt->execute();
$row    = $result->fetchArray(SQLITE3_ASSOC);

if ($row) {
    header('Content-Type: ' . $row['mime']);
    echo $row['data'];
} else {
    http_response_code(404);
    exit('Not found');
}

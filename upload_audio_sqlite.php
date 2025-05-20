<?php
$db = new SQLite3(__DIR__ . '/audios.db');

// ensure table exists
$db->exec("
  CREATE TABLE IF NOT EXISTS audios (
    id       INTEGER PRIMARY KEY AUTOINCREMENT,
    filename TEXT    NOT NULL,
    mime     TEXT    NOT NULL,
    data     BLOB    NOT NULL
  )
");

header('Content-Type: application/json');
$response = ['result' => []];

if (!empty($_FILES['file-0']) && $_FILES['file-0']['error'] === UPLOAD_ERR_OK) {
  $file      = $_FILES['file-0'];
  $rawData   = file_get_contents($file['tmp_name']);
  $safeName  = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($file['name']));

  $stmt = $db->prepare('
        INSERT INTO audios (filename, mime, data)
        VALUES (:fn, :mime, :data)
    ');
  $stmt->bindValue(':fn',   $safeName,       SQLITE3_TEXT);
  $stmt->bindValue(':mime', $file['type'],   SQLITE3_TEXT);
  $stmt->bindValue(':data', $rawData,        SQLITE3_BLOB);

  $result = $stmt->execute();
  $id     = $db->lastInsertRowID();

  if ($id) {
    // This URL will be embedded in the editor
    $url = "get_audio.php?id={$id}";
    $response['result'][] = [
      'url'  => $url,
      'name' => $safeName,
      'size' => $file['size']
    ];
  }
}

echo json_encode($response);

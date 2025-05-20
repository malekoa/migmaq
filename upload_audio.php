<?php
$targetDir = __DIR__ . "/uploads/";
if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

$response = ['result' => []];

if (!empty($_FILES['file-0']) && $_FILES['file-0']['error'] === UPLOAD_ERR_OK) {
    $file      = $_FILES['file-0'];
    $tmpPath   = $file['tmp_name'];
    $origName  = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($file['name']));
    $newName   = time() . '_' . $origName;
    $destPath  = $targetDir . $newName;
    if (move_uploaded_file($tmpPath, $destPath)) {
        // return relative URL or full URL, e.g. "/dashboard/uploads/..."
        $url = "uploads/" . $newName;
        $response['result'][] = [
            'url'  => $url,
            'name' => $origName,
            'size' => $file['size']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($response);

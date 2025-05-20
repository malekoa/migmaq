<?php

class AudioController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function upload(): void
    {
        header('Content-Type: application/json');
        $response = ['result' => []];

        if (!empty($_FILES['file-0']) && $_FILES['file-0']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file-0'];
            $rawData = file_get_contents($file['tmp_name']);
            $safeName = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($file['name']));

            $stmt = $this->pdo->prepare('
                INSERT INTO audios (filename, mime, data)
                VALUES (:fn, :mime, :data)
            ');
            $stmt->bindValue(':fn',   $safeName, PDO::PARAM_STR);
            $stmt->bindValue(':mime', $file['type'], PDO::PARAM_STR);
            $stmt->bindValue(':data', $rawData, PDO::PARAM_LOB);
            $stmt->execute();

            $id = $this->pdo->lastInsertId();

            if ($id) {
                $url = "/audio?id={$id}";
                $response['result'][] = [
                    'url' => $url,
                    'name' => $safeName,
                    'size' => $file['size']
                ];
            }
        }

        echo json_encode($response);
    }

    public function stream(): void
    {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            exit('Missing id');
        }

        $id = (int) $_GET['id'];
        $stmt = $this->pdo->prepare('SELECT mime, data FROM audios WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            header('Content-Type: ' . $row['mime']);
            echo $row['data'];
        } else {
            http_response_code(404);
            echo 'Not found';
        }
    }
}

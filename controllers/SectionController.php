<?php
require_once __DIR__ . '/../models/Section.php';

class SectionController
{
    private Section $sectionModel;

    public function __construct(PDO $pdo)
    {
        $this->sectionModel = new Section($pdo);
    }

    public function fetchForUnit(): void
    {
        $unitId = $_GET['unit_id'] ?? null;
        if (!$unitId) {
            http_response_code(400);
            exit('Missing unit ID');
        }

        $sections = $this->sectionModel->getByUnit($unitId);
        echo json_encode($sections);
    }

    public function save(): void
    {
        $data = [
            'id' => $_POST['sectionId'] ?? null,
            'unit_id' => $_POST['unitId'] ?? null,
            'title' => trim($_POST['sectionTitle'] ?? ''),
            'body' => $_POST['sectionBody'] ?? '',
            'status' => $_POST['sectionStatus'] ?? 'draft',
        ];

        // If we're creating a new section
        if (empty($data['id']) && !$data['unit_id']) {
            throw new Exception('Missing unit ID for new section');
        }

        if ($data['title'] === '') {
            throw new Exception('Missing section title');
        }

        $this->sectionModel->save($data);
        header('Location: /dashboard?status=section_saved');
        exit;
    }

    public function delete(): void
    {
        $id = $_POST['sectionId'] ?? null;
        if ($id) {
            $this->sectionModel->delete($id);
            header('Location: /dashboard?status=section_deleted');
        } else {
            http_response_code(400);
            exit('Missing section ID');
        }
    }

    public function fetch(): void
    {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing ID']);
            return;
        }

        $section = $this->sectionModel->find((int)$_GET['id']);

        if (!$section) {
            http_response_code(404);
            echo json_encode(['error' => 'Not found']);
            return;
        }

        echo json_encode($section);
    }
}

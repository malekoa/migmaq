<?php
require_once __DIR__ . '/../models/Section.php';
require_once __DIR__ . '/../models/Lesson.php';

class DashboardController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        $unitModel = new Unit($this->pdo);
        $sectionModel = new Section($this->pdo);

        $units = $unitModel->all();
        foreach ($units as $i => $unit) {
            $units[$i]['sections'] = $sectionModel->getByUnit($unit['id']);
        }


        require __DIR__ . '/../views/units.php';
    }
    public function unitEditor()
    {
        $this->ensureAuthenticated();
        $units = (new Unit($this->pdo))->all();
        require __DIR__ . '/../views/unit_editor.php';
    }

    public function sectionEditor()
    {
        $this->ensureAuthenticated();

        $unitId = $_GET['unitId'] ?? null;
        if (!$unitId) {
            http_response_code(400);
            exit('Missing unitId');
        }

        $unit = (new Unit($this->pdo))->find($unitId);
        if (!$unit) {
            http_response_code(404);
            exit('Unit not found');
        }

        $sections = (new Section($this->pdo))->getByUnit($unitId);
        require __DIR__ . '/../views/section_editor.php';
    }

    public function lessonEditor()
    {
        $this->ensureAuthenticated();

        $unitId = $_GET['unitId'] ?? null;
        $sectionId = $_GET['sectionId'] ?? null;

        if (!$unitId || !$sectionId) {
            http_response_code(400);
            exit('Missing unitId or sectionId');
        }

        $unitModel = new Unit($this->pdo); // <-- ADD THIS
        $sectionModel = new Section($this->pdo);
        $lessonModel = new Lesson($this->pdo);

        $unit = $unitModel->find($unitId); // <-- ADD THIS
        $section = $sectionModel->find($sectionId);

        if (!$unit || !$section || $section['unit_id'] != $unit['id']) {
            http_response_code(404);
            exit('Not found or mismatched hierarchy');
        }

        $lessons = $lessonModel->getBySection($sectionId);

        require __DIR__ . '/../views/lesson_editor.php';
    }



    private function ensureAuthenticated()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
    }
}

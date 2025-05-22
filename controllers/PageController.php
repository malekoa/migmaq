<?php
require_once __DIR__ . '/../models/Unit.php';
require_once __DIR__ . '/../models/Section.php';
require_once __DIR__ . '/../models/Lesson.php';

class PageController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function landing()
    {
        require __DIR__ . '/../views/landing.php';
    }

    public function showUnit()
    {
        $unitId = $_GET['id'] ?? null;
        if (!$unitId) {
            http_response_code(400);
            exit('Missing unit ID');
        }

        $unitModel = new Unit($this->pdo);
        $sectionModel = new Section($this->pdo);

        $unit = $unitModel->find($unitId);
        if (!$unit) {
            http_response_code(404);
            exit('Unit not found');
        }

        $sections = $sectionModel->getByUnit($unitId);
        $breadcrumbs = [
            ['label' => 'Contents', 'url' => '/contents'],
            ['label' => $unit['title']] // current page, no URL
        ];

        require __DIR__ . '/../views/show_unit.php';
    }

    public function showSection()
    {
        $sectionId = $_GET['id'] ?? null;
        if (!$sectionId) {
            http_response_code(400);
            exit('Missing section ID');
        }

        $sectionModel = new Section($this->pdo);
        $lessonModel = new Lesson($this->pdo);

        $section = $sectionModel->find($sectionId);
        if (!$section) {
            http_response_code(404);
            exit('Section not found');
        }

        $lessons = $lessonModel->getBySection($sectionId);
        $section = $sectionModel->find($sectionId);
        $unit = (new Unit($this->pdo))->find($section['unit_id']);
        $breadcrumbs = [
            ['label' => 'Contents', 'url' => '/contents'],
            ['label' => $unit['title'], 'url' => "/unit?id={$unit['id']}"],
            ['label' => $section['title']]
        ];

        require __DIR__ . '/../views/show_section.php';
    }

    public function showLesson()
    {
        $lessonId = $_GET['id'] ?? null;
        if (!$lessonId) {
            http_response_code(400);
            exit('Missing lesson ID');
        }

        $lessonModel = new Lesson($this->pdo);
        $lesson = $lessonModel->find($lessonId);
        $lesson = $lessonModel->find($lessonId);
        $section = (new Section($this->pdo))->find($lesson['section_id']);
        $unit = (new Unit($this->pdo))->find($section['unit_id']);

        $breadcrumbs = [
            ['label' => 'Contents', 'url' => '/contents'],
            ['label' => $unit['title'], 'url' => "/unit?id={$unit['id']}"],
            ['label' => $section['title'], 'url' => "/section?id={$section['id']}"],
            ['label' => $lesson['title']]
        ];


        if (!$lesson) {
            http_response_code(404);
            exit('Lesson not found');
        }

        require __DIR__ . '/../views/show_lesson.php';
    }
}

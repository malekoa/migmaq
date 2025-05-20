<?php
require_once __DIR__ . '/../models/Section.php';

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


        require __DIR__ . '/../views/dashboard.php';
    }
}

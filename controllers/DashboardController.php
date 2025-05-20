<?php
require_once __DIR__ . '/../models/Unit.php';

class DashboardController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: login');
            exit();
        }

        $unitModel = new Unit($this->pdo);
        $units = $unitModel->all();

        require __DIR__ . '/../views/dashboard.php';
    }
}

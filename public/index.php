<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../controllers/UnitController.php';
require_once __DIR__ . '/../controllers/AudioController.php';
require_once __DIR__ . '/../controllers/SectionController.php';
require_once __DIR__ . '/../controllers/LessonController.php';



// Normalize the path
$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if ($uri === '') $uri = '/';

$method = $_SERVER['REQUEST_METHOD'];

$routes = [
    'GET' => [
        '/' => fn() => (new DashboardController($pdo))->index(),
        '/login' => fn() => (new AuthController($pdo))->showLogin(),
        '/register' => fn() => (new AuthController($pdo))->showRegister(),
        '/logout' => fn() => (new AuthController($pdo))->logout(),
        '/unit/fetch' => fn() => (new UnitController($pdo))->fetch(),
        '/audio' => fn() => (new AudioController($pdo))->stream(),
        '/section/fetch' => fn() => (new SectionController($pdo))->fetch(),
        '/dashboard/unit-editor' => fn() => (new DashboardController($pdo))->unitEditor(),
        '/dashboard/section-editor' => fn() => (new DashboardController($pdo))->sectionEditor(),
        '/dashboard/lesson-editor' => fn() => (new DashboardController($pdo))->lessonEditor(),
        '/lesson/fetch' => fn() => (new LessonController($pdo))->fetch(),
    ],
    'POST' => [
        '/login' => fn() => (new AuthController($pdo))->login(),
        '/register' => fn() => (new AuthController($pdo))->register(),
        '/unit/save' => fn() => (new UnitController($pdo))->save(),
        '/unit/delete' => fn() => (new UnitController($pdo))->delete(),
        '/unit/update-order' => fn() => (new UnitController($pdo))->updateOrder(),
        '/audio/upload' => fn() => (new AudioController($pdo))->upload(),
        '/section/save' => fn() => (new SectionController($pdo))->save(),
        '/section/delete' => fn() => (new SectionController($pdo))->delete(),
        '/section/update-order' => fn() => (new SectionController($pdo))->updateOrder(),
        '/lesson/save' => fn() => (new LessonController($pdo))->save(),
        '/lesson/delete' => fn() => (new LessonController($pdo))->delete(),
        '/lesson/update-order' => fn() => (new LessonController($pdo))->updateOrder(),
    ]
];

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (isset($routes[$method][$path])) {
    $handler = $routes[$method][$path];
    if (is_callable($handler)) {
        $handler();
    } else {
        require $handler;
    }
} else {
    http_response_code(404);
    echo "404 Not Found";
}

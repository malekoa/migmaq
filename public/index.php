<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../controllers/UnitController.php';
require_once __DIR__ . '/../controllers/AudioController.php';

// Normalize the path
$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if ($uri === '') $uri = '/';

$method = $_SERVER['REQUEST_METHOD'];

// Simple routing table
$routes = [
    'GET' => [
        '/' => fn() => (new DashboardController($pdo))->index(),
        '/dashboard' => fn() => (new DashboardController($pdo))->index(),
        '/login' => fn() => (new AuthController($pdo))->showLogin(),
        '/register' => fn() => (new AuthController($pdo))->showRegister(),
        '/logout' => fn() => (new AuthController($pdo))->logout(),
        '/unit/fetch' => fn() => (new UnitController($pdo))->fetch(),
        '/audio' => fn() => (new AudioController($pdo))->stream(),
    ],
    'POST' => [
        '/login' => fn() => (new AuthController($pdo))->login(),
        '/register' => fn() => (new AuthController($pdo))->register(),
        '/unit/save' => fn() => (new UnitController($pdo))->save(),
        '/unit/delete' => fn() => (new UnitController($pdo))->delete(),
        '/unit/update-order' => fn() => (new UnitController($pdo))->updateOrder(),
        '/audio/upload' => fn() => (new AudioController($pdo))->upload(),
    ]
];

// Dispatcher
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (isset($routes[$method][$path])) {
    $handler = $routes[$method][$path];
    if (is_callable($handler)) {
        $handler(); // Call controller method
    } else {
        require $handler;
    }
} else {
    http_response_code(404);
    echo "404 Not Found";
}

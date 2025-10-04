<?php
// public/index.php - Front controller
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../app/core/Helpers.php';
require_once __DIR__ . '/../app/core/Router.php';

// Auto-loader for app classes
spl_autoload_register(function($class){
    $paths = [
        __DIR__ . '/../app/controllers/' . $class . '.php',
        __DIR__ . '/../app/models/' . $class . '.php',
        __DIR__ . '/../app/core/' . $class . '.php',
    ];
    foreach ($paths as $p) {
        if (file_exists($p)) { require_once $p; return; }
    }
});

ensure_session_started();
csrf_token(); // ensure a token exists

// Define routes
$router = new Router();

$router->get('/', function(){ redirect('/auth/login'); });
$router->get('/auth/login', [AuthController::class, 'showLogin']);
$router->get('/auth/register', [AuthController::class, 'showRegister']);
$router->post('/auth/register', [AuthController::class, 'register']);
$router->post('/auth/login', [AuthController::class, 'login']);
$router->get('/auth/logout', [AuthController::class, 'logout']);

$router->get('/profile', [ProfileController::class, 'index']);

// Dispatch
$router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');

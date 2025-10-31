<?php
// public/index.php - Simple fallback (Router and Helpers not implemented yet)
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Redirect to home page for now (bypass login)
header('Location: /app/views/pages/posts/home.php');
exit();

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

// Auth routes
$router->get('/', function(){ redirect('/auth/login'); });
$router->get('/auth/login', [AuthController::class, 'showLogin']);
$router->get('/auth/register', [AuthController::class, 'showRegister']);
$router->post('/auth/register', [AuthController::class, 'register']);
$router->post('/auth/login', [AuthController::class, 'login']);
$router->get('/auth/logout', [AuthController::class, 'logout']);

// Main pages
$router->get('/home', function(){ require __DIR__ . '/../app/views/pages/posts/home.php'; });
$router->get('/profile', [ProfileController::class, 'index']);
$router->get('/friends', function(){ require __DIR__ . '/../app/views/pages/friends/index.php'; });
$router->get('/messages', function(){ require __DIR__ . '/../app/views/pages/messages/index.php'; });
$router->get('/notifications', function(){ require __DIR__ . '/../app/views/pages/notifications/index.php'; });
$router->get('/settings', function(){ require __DIR__ . '/../app/views/pages/settings/index.php'; });

// Posts
$router->get('/posts/:id', function($id){ 
    $_GET['id'] = $id;
    require __DIR__ . '/../app/views/pages/posts/detail.php'; 
});

// Dispatch
$router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');

<?php
// public/index.php - Front controller
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

if (file_exists(__DIR__ . '/../app/core/Helpers.php')) require_once __DIR__ . '/../app/core/Helpers.php';
if (file_exists(__DIR__ . '/../app/core/Router.php')) require_once __DIR__ . '/../app/core/Router.php';

// Auto-loader for app classes

// Fallback helpers if missing
if (!function_exists('redirect')) {
    function redirect($path){
        header("Location: " . $path);
        exit;
    }
}
// Minimal Router fallback if class not available
if (!class_exists('Router')) {
    class Router {
        private array $get = [];
        private array $post = [];
        public function get($path, $handler){ $this->get[$path] = $handler; }
        public function post($path, $handler){ $this->post[$path] = $handler; }
        public function dispatch($uri, $method){
            $path = parse_url($uri, PHP_URL_PATH) ?? '/';
            $table = strtoupper($method) === 'POST' ? $this->post : $this->get;
            if (isset($table[$path])) {
                $h = $table[$path];
                if (is_callable($h)) return $h();
                if (is_array($h) && count($h) === 2) {
                    [$cls, $fn] = $h;
                    $obj = new $cls();
                    return $obj->$fn();
                }
            }
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}

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
$router->get('/auth/forgot', [AuthController::class, 'showForgot']);
$router->post('/auth/register', [AuthController::class, 'register']);
$router->post('/auth/login', [AuthController::class, 'login']);
$router->post('/auth/forgot', [AuthController::class, 'forgot']);
$router->get('/auth/logout', [AuthController::class, 'logout']);

$router->get('/profile', [ProfileController::class, 'index']);
$router->get('/home', function(){ require __DIR__ . '/../app/views/pages/posts/home.php'; });

// Dispatch
$router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');

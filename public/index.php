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
// Ensure session helper (fallback)
if (!function_exists('ensure_session_started')) {
    function ensure_session_started(){
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}

// CSRF token helper (fallback)
if (!function_exists('csrf_token')) {
    function csrf_token(){
        ensure_session_started();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
        }
        return $_SESSION['csrf_token'];
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
            // Render a friendly HTML page with toast-like notification so missing routes
            // inside the application show a modal/toast instead of raw text.
            header('Content-Type: text/html; charset=utf-8');
            $msg = htmlspecialchars('Trang không tìm thấy (404)');
            // Try to include the toast component if available
            $toastPath = __DIR__ . '/../app/views/components/layout/toast.php';
            $toastHtml = file_exists($toastPath) ? file_get_contents($toastPath) : '';
            echo '<!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
            echo '<title>404 - Không tìm thấy</title>';
            echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
            echo '</head><body style="padding:2rem;">';
            echo '<div class="container"><h1>Không tìm thấy</h1><p>Yêu cầu tới <strong>' . htmlspecialchars($path) . '</strong> không tìm thấy trên máy chủ.</p></div>';
            // inject the toast component (raw HTML + scripts)
            if ($toastHtml) {
                // the toast component contains inline scripts that define showErrorToast()
                echo $toastHtml;
            } else {
                // fallback simple inline script to show an alert-like notification
                echo '<div class="alert alert-danger" role="alert">' . $msg . '</div>';
            }
            // If toast component exists, call the showErrorToast function
            if ($toastHtml) {
                echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>';
                echo '<script>document.addEventListener("DOMContentLoaded",function(){ if(typeof showErrorToast==="function"){ showErrorToast("' . addslashes($msg) . '"); } else { alert("' . addslashes($msg) . '"); } });</script>';
            }
            echo '</body></html>';
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

// Load config if not already loaded
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config/config.php';
}

// Set up asset URL if not defined
if (!defined('ASSETS_URL')) {
    define('ASSETS_URL', rtrim(BASE_URL, '/') . '/assets');
}

// Define routes
$router = new Router();

// Get the request URI and remove BASE_URL prefix
$requestUri = $_SERVER['REQUEST_URI'];
$basePrefix = rtrim(BASE_URL, '/');
if ($basePrefix && strpos($requestUri, $basePrefix) === 0) {
    $requestUri = substr($requestUri, strlen($basePrefix));
}
if (empty($requestUri)) {
    $requestUri = '/';
}

// Homepage redirect
$router->get('/', function(){ redirect(BASE_URL . '/auth/login'); });

// Auth routes
$router->get('/auth/login', [AuthController::class, 'showLogin']);
$router->get('/auth/register', [AuthController::class, 'showRegister']);
$router->get('/auth/forgot', [AuthController::class, 'showForgot']);
$router->post('/auth/register', [AuthController::class, 'register']);
$router->post('/auth/login', [AuthController::class, 'login']);
$router->post('/auth/forgot', [AuthController::class, 'forgot']);
$router->get('/auth/logout', [AuthController::class, 'logout']);

// User routes
$router->get('/profile', [ProfileController::class, 'index']);
$router->get('/friends', [FriendController::class, 'index']);
$router->get('/messages', [MessageController::class, 'index']);
$router->get('/notifications', [NotificationController::class, 'index']);

// Posts & Feed
$router->get('/home', function(){ require __DIR__ . '/../app/views/pages/posts/home.php'; });
$router->get('/posts/create', [PostController::class, 'create']);
$router->get('/posts/:id', [PostController::class, 'show']);

// Get request method
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Dispatch the route with the processed URI
$router->dispatch($requestUri, $method);

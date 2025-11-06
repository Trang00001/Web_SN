<?php
/**
 * Entry Point - public/index.php
 * All requests route through here
 */

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Load core files
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../config/config.php';

// Auto-loader for app classes
spl_autoload_register(function($class){
    $paths = [
        __DIR__ . '/../app/controllers/' . $class . '.php',
        __DIR__ . '/../app/models/' . $class . '.php',
        __DIR__ . '/../core/' . $class . '.php',
    ];
    foreach ($paths as $p) {
        if (file_exists($p)) { 
            require_once $p; 
            return; 
        }
    }
});

// Initialize session
ensure_session_started();

// Get request URI and method
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Get clean path
$path = parse_url($uri, PHP_URL_PATH) ?? '/';
$path = preg_replace('#^/public#', '', $path); // Remove /public/ prefix

// Debug log
error_log("Request: $method $path");

// Handle root path - Check authentication FIRST
if ($path === '/' || $path === '' || $path === '/index.php') {
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        error_log("User not authenticated, redirecting to /login");
        header('Location: /login');
        exit();
    }
    error_log("User authenticated (ID: {$_SESSION['user_id']}), redirecting to /home");
    header('Location: /home');
    exit();
}

// Create router instance
$router = new Router(__DIR__ . '/..');

// Load AuthController
require_once __DIR__ . '/../app/controllers/AuthController.php';

// Define authentication routes (public access)
$router->get('/login', function(){ 
    require __DIR__ . '/../app/views/pages/auth/login.php'; 
});
$router->get('/register', function(){ 
    require __DIR__ . '/../app/views/pages/auth/register.php'; 
});
$router->get('/forgot', function(){ 
    require __DIR__ . '/../app/views/pages/auth/forgot_password.php'; 
});
$router->get('/auth/forgot', function(){ 
    require __DIR__ . '/../app/views/pages/auth/forgot_password.php'; 
});

// Auth API endpoints - POST requests
$router->post('/auth/login', function() {
    $auth = new AuthController();
    $auth->login();
});
$router->post('/auth/register', function() {
    $auth = new AuthController();
    $auth->register();
});
$router->get('/auth/logout', function() {
    $auth = new AuthController();
    $auth->logout();
});
$router->post('/auth/forgot', function() {
    $auth = new AuthController();
    $auth->resetPassword();
});

// Friend API endpoints
require_once __DIR__ . '/../app/controllers/FriendController.php';
$router->get('/api/friends/all', function() {
    $controller = new FriendController();
    $controller->getAllFriends();
});
$router->get('/api/friends/requests', function() {
    $controller = new FriendController();
    $controller->getFriendRequests();
});
$router->post('/api/friends/accept', function() {
    $controller = new FriendController();
    $controller->acceptRequest();
});
$router->post('/api/friends/reject', function() {
    $controller = new FriendController();
    $controller->rejectRequest();
});
$router->post('/api/friends/remove', function() {
    $controller = new FriendController();
    $controller->removeFriend();
});
$router->post('/api/friends/send_request', function() {
    $controller = new FriendController();
    $controller->sendRequest();
});
// Suggested friends
$router->get('/api/friends/suggested', function() {
    $controller = new FriendController();
    $controller->getSuggestedFriends();
});

// Message API endpoints
$router->get('/api/messages/fetch', function() {
    $_GET['action'] = 'fetch';
    require __DIR__ . '/../app/controllers/MessageController.php';
});
$router->post('/api/messages/send', function() {
    $_POST['action'] = 'send';
    require __DIR__ . '/../app/controllers/MessageController.php';
});

// Protected pages - require authentication
$router->get('/home', function(){ 
    require __DIR__ . '/../app/views/pages/posts/home.php'; 
});
$router->get('/friends', function(){ 
    require __DIR__ . '/../app/views/pages/friends/index.php'; 
});
$router->get('/friends/requests', function(){ 
    require __DIR__ . '/../app/views/pages/friends/requests.php'; 
});
$router->get('/messages', function(){ 
    require __DIR__ . '/../app/views/pages/messages/index.php'; 
});
$router->get('/messages/chat', function(){ 
    require __DIR__ . '/../app/views/pages/messages/chat.php'; 
});
$router->get('/profile', function(){ 
    require_once __DIR__ . '/../app/controllers/ProfileController.php';
    $controller = new ProfileController();
    $controller->index();
});
$router->get('/profile/edit', function(){ 
    require_once __DIR__ . '/../app/controllers/ProfileController.php';
    $controller = new ProfileController();
    $controller->edit();
});
$router->post('/profile/edit', function(){ 
    require_once __DIR__ . '/../app/controllers/ProfileController.php';
    $controller = new ProfileController();
    $controller->update();
});
$router->get('/notifications', function(){ 
    require __DIR__ . '/../app/views/pages/notifications/index.php'; 
});
$router->get('/search', function(){ 
    require __DIR__ . '/../app/views/pages/search/index.php'; 
});

// Post detail with dynamic ID
$router->get('/posts/:id', function($id){ 
    $_GET['id'] = $id;
    require __DIR__ . '/../app/views/pages/posts/detail.php'; 
});

// Dispatch the request
$router->dispatch($uri, $method);

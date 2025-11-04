<?php
/**
 * Router script for PHP built-in server
 * Uses Router class from core/Router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly (CSS, JS, images, uploads)
// Check in both root and public directory
if ($uri !== '/') {
    $possiblePaths = [
        __DIR__ . $uri,                    // Root directory
        __DIR__ . '/public' . $uri,        // Public directory
    ];
    
    foreach ($possiblePaths as $path) {
        if (file_exists($path) && is_file($path)) {
            return false; // Let PHP built-in server handle static files
        }
    }
}

// Load Router class
require_once __DIR__ . '/core/Router.php';

$router = new Router(__DIR__);

// Define all routes
$routes = [
    '/home' => '/app/views/pages/posts/home.php',
    '/login' => '/app/views/pages/auth/login.php',
    '/register' => '/app/views/pages/auth/register.php',
    '/auth/login' => '/app/api/auth.php?action=login',
    '/auth/register' => '/app/api/auth.php?action=register',
    '/auth/logout' => '/app/api/auth.php?action=logout',
    '/auth/forgot' => '/app/api/auth.php?action=forgot',
    '/friends' => '/app/views/pages/friends/index.php',
    '/friends/requests' => '/app/views/pages/friends/requests.php',
    '/messages' => '/app/views/pages/messages/index.php',
    '/messages/chat' => '/app/views/pages/messages/chat.php',
    '/chat.php' => '/app/views/pages/messages/chat.php',
    '/profile' => '/app/views/pages/profile/profile.php',
    '/profile/edit' => '/app/views/pages/profile/edit_profile.php',
    '/notifications' => '/app/views/pages/notifications/index.php',
    '/search' => '/app/views/pages/search/index.php',
];

// Add static routes
$router->addStaticRoutes($routes);

// Route root to index.php
if ($uri === '/' || $uri === '') {
    require __DIR__ . '/index.php';
    return true;
}

// Dispatch request
$router->dispatch($uri, $_SERVER['REQUEST_METHOD']);
return true;

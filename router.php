<?php
/**
 * Router script for PHP built-in server
 * This allows proper routing and static file serving
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly (CSS, JS, images)
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; // Let PHP built-in server handle static files
}

// Route dynamic requests
if ($uri === '/' || $uri === '') {
    require __DIR__ . '/index.php';
    return true;
}

// Custom routes mapping
$routes = [
    '/home' => '/app/views/pages/posts/home.php',
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

// Check custom routes
if (isset($routes[$uri])) {
    $file = __DIR__ . $routes[$uri];
    if (file_exists($file)) {
        chdir(dirname($file));
        require $file;
        return true;
    }
}

// Check if requested PHP file exists
$phpFile = __DIR__ . $uri;
if (preg_match('/\.php$/', $uri)) {
    if (file_exists($phpFile)) {
        // Change directory to the file's directory for relative includes
        chdir(dirname($phpFile));
        require $phpFile;
        return true;
    }
}

// Check public directory
$publicFile = __DIR__ . '/public' . $uri;
if (file_exists($publicFile)) {
    if (preg_match('/\.php$/', $uri)) {
        require $publicFile;
        return true;
    }
    return false; // Static file in public
}

// 404 Not Found
http_response_code(404);
echo "<!DOCTYPE html><html><head><title>404 Not Found</title></head>";
echo "<body><h1>404 Not Found</h1>";
echo "<p>The requested resource <code>" . htmlspecialchars($uri) . "</code> was not found.</p>";
echo "<p><a href='/'>Go to homepage</a></p>";
echo "</body></html>";
return true;

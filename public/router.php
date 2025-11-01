<?php
/**
 * Router for PHP built-in server when running from public/ directory
 * Usage: cd public && php -S localhost:8000 router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
$publicPath = __DIR__ . $uri;

// Serve existing static files (CSS, JS, images, etc)
if ($uri !== '/' && file_exists($publicPath) && !is_dir($publicPath)) {
    return false; // Let PHP built-in server handle it
}

// Route everything else through index.php
require_once __DIR__ . '/index.php';

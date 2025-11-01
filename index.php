<?php
/**
 * Root Entry point - Redirect to public folder
 * This file is for Apache/XAMPP setup
 */

// Redirect all requests to public folder
$requestUri = $_SERVER['REQUEST_URI'];
$publicPath = '/public' . $requestUri;

header('Location: ' . $publicPath);
exit();
?>
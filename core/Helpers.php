<?php
// app/core/Helpers.php

if (!function_exists('ensure_session_started')) {
    function ensure_session_started() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}

if (!function_exists('redirect')) {
    function redirect($path) {
        $base = defined('BASE_URL') ? BASE_URL : '';
        if (strpos($path, 'http') !== 0) {
            $path = $base . $path;
        }
        header("Location: " . $path);
        exit;
    }
}

if (!function_exists('url')) {
    function url($path = '') {
        $base = defined('BASE_URL') ? BASE_URL : '';
        return $base . ($path ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        $base = defined('ASSETS_URL') ? ASSETS_URL : '/assets';
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token() {
        ensure_session_started();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('check_csrf')) {
    function check_csrf($token) {
        ensure_session_started();
        return isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('require_auth')) {
    function require_auth() {
        ensure_session_started();
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            redirect('/login');
            exit();
        }
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        ensure_session_started();
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
}

// Add JS-friendly URL helpers to all views
if (!function_exists('inject_js_urls')) {
    function inject_js_urls() {
        $base = defined('BASE_URL') ? BASE_URL : '';
        $assets = defined('ASSETS_URL') ? ASSETS_URL : '/assets';
        return sprintf(
            '<script>window.BASE_URL="%s";window.ASSETS_URL="%s";</script>',
            htmlspecialchars($base),
            htmlspecialchars($assets)
        );
    }
}
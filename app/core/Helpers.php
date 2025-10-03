<?php
// app/core/Helpers.php
function ensure_session_started() {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
}

function csrf_token(): string {
    ensure_session_started();
    if (empty($_SESSION['_token'])) {
        $_SESSION['_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['_token'];
}

function csrf_validate(?string $token): bool {
    ensure_session_started();
    return isset($_SESSION['_token']) && is_string($token) && hash_equals($_SESSION['_token'], $token);
}

function redirect(string $path) {
    header("Location: " . $path);
    exit;
}

function view_include(string $path, array $vars = []) {
    extract($vars);
    include $path;
}

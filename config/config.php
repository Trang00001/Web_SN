<?php
// Cấu hình cơ sở dữ liệu
define("HOST", "localhost");
define("DB", "SocialNetworkDB");
define("USER", "root");
define("PASSWORD", "");

// Base URL - tự động detect
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
if (strpos($scriptPath, '/public') !== false) {
    define("BASE_URL", "");
} else {
    $basePath = str_replace('/public', '', $scriptPath);
    $basePath = rtrim($basePath, '/');
    define("BASE_URL", $basePath);
}

// Assets URL
define("ASSETS_URL", (defined('BASE_URL') ? BASE_URL : '') . '/assets');

// Thiết lập charset mặc định
define("DB_CHARSET", "utf8mb4");
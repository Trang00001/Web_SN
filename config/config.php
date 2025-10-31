<?php
// Cấu hình cơ sở dữ liệu
define("HOST", "localhost");
define("DB", "SocialNetworkDB");
define("USER", "root");
define("PASSWORD", "");

// Base URL - tự động detect
// Khi serve từ public folder: BASE_URL = ""
// Khi serve từ XAMPP htdocs/WEB-SN: BASE_URL = "/WEB-SN"
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
if (strpos($scriptPath, '/public') !== false) {
    // Serving from public folder (built-in server)
    define("BASE_URL", "");
} else {
    // Serving from XAMPP or other web server
    $basePath = str_replace('/public', '', $scriptPath);
    $basePath = rtrim($basePath, '/');
    define("BASE_URL", $basePath);
}

// Thiết lập charset mặc định
define("DB_CHARSET", "utf8mb4");
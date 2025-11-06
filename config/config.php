<?php
// Cấu hình cơ sở dữ liệu
define("HOST", "localhost");
define("DB", "SocialNetworkDB");
define("USER", "root");
define("PASSWORD", "");

// Base URL - tự động detect, hỗ trợ chạy dưới thư mục con (vd: /Web_SN/public)
$scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
$scriptDir = str_replace('\\', '/', $scriptDir);
$scriptDir = rtrim($scriptDir, '/');

// Khi chạy bằng PHP built-in server với document root là public, $scriptDir sẽ là '/'
// -> BASE_URL rỗng để tạo đường dẫn như '/profile'
if ($scriptDir === '' || $scriptDir === '/') {
    define("BASE_URL", "");
} else {
    // Ví dụ: '/Web_SN/public' => dùng nguyên vẹn để tạo URL tuyệt đối đúng
    define("BASE_URL", $scriptDir);
}

// Assets URL
define("ASSETS_URL", (defined('BASE_URL') ? BASE_URL : '') . '/assets');

// Thiết lập charset mặc định
define("DB_CHARSET", "utf8mb4");
<?php
require_once __DIR__ . "/core/Database.php";

// Khởi tạo DB
$db = new Database();

// Dùng hàm select() có sẵn
$data = $db->select("SELECT * FROM user LIMIT 5"); // đổi tên bảng đúng theo schema

if (!empty($data)) {
    echo "✅ Kết nối DB & SELECT thành công<br>";
    echo "<pre>";
    print_r($data);
    echo "</pre>";
} else {
    echo "⚠️ Không có dữ liệu hoặc query lỗi";
}

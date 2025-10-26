<?php
require_once __DIR__ . '/../app/models/Account.php';

// Tạo username ngẫu nhiên để tránh trùng lặp
$random = substr(md5(rand()), 0, 7);
$email = "test{$random}@example.com";
$username = "tester{$random}";

try {
    // Tạo tài khoản mới với thông tin ngẫu nhiên
    $acc = new Account($email, password_hash("123456", PASSWORD_DEFAULT), $username);
    if ($acc->register()) {
        echo "✅ Đăng ký thành công với:<br>";
        echo "- Email: {$email}<br>";
        echo "- Username: {$username}<br>";
        
        // Kiểm tra xem có tìm được tài khoản vừa tạo không
        $found = $acc->findByEmail($email);
        if ($found) {
            echo "✅ Tìm thấy tài khoản trong database<br>";
            echo "<pre>";
            print_r($found);
            echo "</pre>";
        }
    } else {
        echo "❌ Đăng ký thất bại<br>";
    }
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "<br>";
}

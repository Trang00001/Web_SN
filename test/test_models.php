<?php
require_once __DIR__ . '/../app/models/Account.php';

// Tạo tài khoản mới
$acc = new Account("test2@example.com", password_hash("123456", PASSWORD_DEFAULT), "tester2");
if ($acc->register()) {
    echo "✅ Đăng ký thành công<br>";
} else {
    echo "❌ Đăng ký thất bại<br>";
}


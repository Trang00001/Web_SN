<?php
require_once __DIR__ . '/../models/Account.php';

// Tạo tài khoản mới
$acc = new Account();
$acc->setEmail("test@example.com");
$acc->setPasswordHash(password_hash("123456", PASSWORD_DEFAULT));
$acc->setUsername("tester");

if ($acc->create()) {
    echo "✅ Tạo tài khoản thành công<br>";
} else {
    echo "❌ Lỗi khi tạo tài khoản<br>";
}

// Lấy thông tin theo email
$data = $acc->getByEmail("test@example.com");
echo "<pre>";
print_r($data);
echo "</pre>";
?>

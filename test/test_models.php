<?php
require_once __DIR__ . '/../app/models/User.php';

echo "<pre>";
/*
// 1. Tạo user mới
$user = new User("bob2", "bob2@example.com", "123456");
if ($user->save()) {
    echo "Đã tạo user bob thành công!\n";
} else {
    echo "Tạo user thất bại!\n";
}
*/
// 2. Lấy danh sách user
$user = User::findById(1);
print_r($user);

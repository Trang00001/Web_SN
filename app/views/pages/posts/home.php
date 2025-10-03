<?php
// pages/home.php

// Nội dung trang chủ
ob_start();
?>
<div class="container mt-4">
    <h1>Trang chủ</h1>
    <p>Chào mừng bạn đến với mạng xã hội mini!</p>
</div>
<?php
$content = ob_get_clean();

// Gọi layout main
include __DIR__ . '/../../layouts/main.php';


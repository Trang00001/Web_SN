
<?php
// pages/login.php

// Nội dung form login
ob_start();
?>
<div class="login-form">
    <h2>Đăng nhập</h2>
    <form method="POST" action="do_login.php">
        <div class="mb-3">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password">Mật khẩu:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Đăng nhập</button>
    </form>
</div>
<?php
$content = ob_get_clean();

// Gọi layout auth
include __DIR__ . '/../../layouts/auth.php';


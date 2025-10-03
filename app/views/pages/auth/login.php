<?php
// app/views/pages/auth/login.php
?><!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập - Social Network</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= $BASE ?>/public/assets/css/auth.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="auth-page">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm rounded-4">
          <div class="card-body p-4 p-md-5">
            <h1 class="h4 text-center mb-3">Chào mừng trở lại</h1>
            <p class="text-center text-muted mb-4">Đăng nhập để tiếp tục</p>
            <form id="formLoginPage" method="post" action="/auth/login" novalidate>
              <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['_token'] ?? '') ?>">
              <div class="mb-3">
                <label for="login_email" class="form-label">Email</label>
                <input type="email" class="form-control" id="login_email" name="email" placeholder="name@example.com" required>
                <div class="invalid-feedback">Vui lòng nhập email hợp lệ.</div>
              </div>
              <div class="mb-3">
                <label for="login_password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="login_password" name="password" minlength="6" required>
                <div class="invalid-feedback">Mật khẩu tối thiểu 6 ký tự.</div>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" id="rememberMe" name="remember">
                  <label class="form-check-label" for="rememberMe">Ghi nhớ tôi</label>
                </div>
                <a href="/auth/forgot" class="small text-decoration-none">Quên mật khẩu?</a>
              </div>
              <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
            </form>
            <div class="text-center mt-3">
              <span class="text-muted">Chưa có tài khoản?</span>
              <a href="/auth/register" class="text-decoration-none">Đăng ký</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= $BASE ?>/public/assets/js/validation.js"></script>
  <script>
    window.addEventListener('DOMContentLoaded', () => {
      window.attachBasicValidation('#formLoginPage', {confirm: false});
    });
  </script>
</body>
</html>

<?php
// app/views/pages/auth/register.php
?><!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng ký - Social Network</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= $BASE ?>/public/assets/css/auth.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="auth-page">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm rounded-4">
          <div class="card-body p-4 p-md-5">
            <h1 class="h4 text-center mb-3">Tạo tài khoản</h1>
            <p class="text-center text-muted mb-4">Miễn phí và nhanh chóng</p>
            <form id="formRegisterPage" method="post" action="/auth/register" novalidate>
              <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['_token'] ?? '') ?>">
              <div class="row g-3">
                <div class="col-12">
                  <label for="reg_username" class="form-label">Tên người dùng</label>
                  <input type="text" class="form-control" id="reg_username" name="username" minlength="3" required>
                  <div class="invalid-feedback">Tên người dùng tối thiểu 3 ký tự.</div>
                </div>
                <div class="col-12">
                  <label for="reg_email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="reg_email" name="email" placeholder="name@example.com" required>
                  <div class="invalid-feedback">Vui lòng nhập email hợp lệ.</div>
                </div>
                <div class="col-md-6">
                  <label for="reg_password" class="form-label">Mật khẩu</label>
                  <input type="password" class="form-control" id="reg_password" name="password" minlength="6" required>
                  <div class="invalid-feedback">Mật khẩu tối thiểu 6 ký tự.</div>
                </div>
                <div class="col-md-6">
                  <label for="reg_confirm" class="form-label">Xác nhận mật khẩu</label>
                  <input type="password" class="form-control" id="reg_confirm" name="confirm" minlength="6" required>
                  <div class="invalid-feedback">Xác nhận mật khẩu chưa khớp.</div>
                </div>
              </div>
              <button type="submit" class="btn btn-success w-100 mt-3">Đăng ký</button>
            </form>
            <div class="text-center mt-3">
              <span class="text-muted">Đã có tài khoản?</span>
              <a href="/auth/login" class="text-decoration-none">Đăng nhập</a>
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
      window.attachBasicValidation('#formRegisterPage', {confirm: true});
    });
  </script>
</body>
</html>

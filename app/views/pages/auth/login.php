<?php /* Full login page */ ?>
<link rel="stylesheet" href="/assets/css/auth.css">
<div class="auth-wrapper container">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h3 class="mb-3 text-center">Đăng nhập</h3>
          <?php if (!empty($_SESSION['flash'])): ?>
            <div class="alert alert-<?=$_SESSION['flash']['type'] ?? 'info'?>">
              <?=htmlspecialchars($_SESSION['flash']['message'])?>
            </div>
            <?php unset($_SESSION['flash']); endif; ?>
          <form method="POST" action="/auth/login" data-validate="login" novalidate>
            <div class="mb-3">
              <label class="form-label">Email hoặc Username</label>
              <input type="text" name="identity" class="form-control" required>
              <div class="invalid-feedback">Vui lòng nhập email/username.</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Mật khẩu</label>
              <input type="password" name="password" class="form-control" minlength="6" required>
              <div class="invalid-feedback">Mật khẩu tối thiểu 6 ký tự.</div>
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-primary flex-fill" type="submit">Đăng nhập</button>
              <a class="btn btn-outline-secondary flex-fill" href="/auth/register">Tạo tài khoản</a>
            </div>
          </form>
        </div>
      </div>
      <p class="text-center mt-3"><a href="/">← Về trang chủ</a></p>
    </div>
  </div>
</div>
<script src="/assets/js/validation.js"></script>

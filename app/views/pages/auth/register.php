<?php /* Full register page */ ?>
<link rel="stylesheet" href="/assets/css/auth.css">
<div class="auth-wrapper container">
  <div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h3 class="mb-3 text-center">Đăng ký</h3>
          <form method="POST" action="/auth/register" data-validate="register" novalidate>
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Tên hiển thị</label>
                <input type="text" name="name" class="form-control" required>
                <div class="invalid-feedback">Không được để trống tên.</div>
              </div>
              <div class="col-12">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
                <div class="invalid-feedback">Email không hợp lệ.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" minlength="6" required>
                <div class="invalid-feedback">Mật khẩu tối thiểu 6 ký tự.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Nhập lại mật khẩu</label>
                <input type="password" name="password_confirm" class="form-control" minlength="6" required>
                <div class="invalid-feedback">Mật khẩu nhập lại chưa khớp.</div>
              </div>
              <div class="col-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="agree_full" required>
                  <label class="form-check-label" for="agree_full">Tôi đồng ý với điều khoản.</label>
                  <div class="invalid-feedback">Bạn cần đồng ý điều khoản.</div>
                </div>
              </div>
            </div>
            <button class="btn btn-success w-100 mt-3" type="submit">Tạo tài khoản</button>
            <p class="text-center mt-3 mb-0">Đã có tài khoản? <a href="/auth/login">Đăng nhập</a></p>
          </form>
        </div>
      </div>
      <p class="text-center mt-3"><a href="/">← Về trang chủ</a></p>
    </div>
  </div>
</div>
<script src="/assets/js/validation.js"></script>

<?php /* Popup Đăng nhập (UI + submit cơ bản) */ ?>
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content auth-modal">
      <div class="modal-header">
        <h5 class="modal-title">Đăng nhập</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <form method="POST" action="/auth/login" data-validate="login" novalidate>
        <div class="modal-body">
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
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
            <label class="form-check-label" for="rememberMe">Ghi nhớ đăng nhập</label>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary w-100" type="submit">Đăng nhập</button>
        </div>
      </form>
    </div>
  </div>
</div>

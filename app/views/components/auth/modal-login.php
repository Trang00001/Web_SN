<?php
// app/views/components/auth/modal-login.php
// Bootstrap 5 modal: Login
$csrf = $_SESSION['_token'] ?? bin2hex(random_bytes(16));
$_SESSION['_token'] = $csrf;
?>
<div class="modal fade" id="modalLogin" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa-solid fa-right-to-bracket me-2"></i>Đăng nhập</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
  <form id="formLogin" method="post" action="<?php echo htmlspecialchars(defined('BASE_URL') ? BASE_URL : ''); ?>/auth/login" novalidate>
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf) ?>">
        <div class="modal-body">
          <div class="mb-3">
            <label for="login_email" class="form-label">Email</label>
            <input type="email" class="form-control" id="login_email" name="email" placeholder="name@example.com" required>
            <div class="invalid-feedback">Vui lòng nhập email hợp lệ.</div>
          </div>
          <div class="mb-2">
            <label for="login_password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="login_password" name="password" minlength="6" required>
            <div class="invalid-feedback">Mật khẩu tối thiểu 6 ký tự.</div>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="1" id="rememberMe" name="remember">
              <label class="form-check-label" for="rememberMe">Ghi nhớ tôi</label>
            </div>
            <a href="<?php echo htmlspecialchars(defined('BASE_URL') ? BASE_URL : ''); ?>/auth/forgot" class="text-decoration-none small">Quên mật khẩu?</a>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Đăng nhập</button>
        </div>
      </form>
    </div>
  </div>
</div>

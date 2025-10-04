<?php
// app/views/components/auth/modal-register.php
$csrf = $_SESSION['_token'] ?? bin2hex(random_bytes(16));
$_SESSION['_token'] = $csrf;
?>
<div class="modal fade" id="modalRegister" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa-solid fa-user-plus me-2"></i>Đăng ký</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formRegister" method="post" action="/auth/register" novalidate>
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf) ?>">
        <div class="modal-body">
          <div class="mb-3">
            <label for="reg_username" class="form-label">Tên người dùng</label>
            <input type="text" class="form-control" id="reg_username" name="username" minlength="3" required>
            <div class="invalid-feedback">Tên người dùng tối thiểu 3 ký tự.</div>
          </div>
          <div class="mb-3">
            <label for="reg_email" class="form-label">Email</label>
            <input type="email" class="form-control" id="reg_email" name="email" placeholder="name@example.com" required>
            <div class="invalid-feedback">Vui lòng nhập email hợp lệ.</div>
          </div>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="reg_password" class="form-label">Mật khẩu</label>
              <input type="password" class="form-control" id="reg_password" name="password" minlength="6" required>
              <div class="invalid-feedback">Mật khẩu tối thiểu 6 ký tự.</div>
            </div>
            <div class="col-md-6">
              <label for="reg_confirm" class="form-label">Xác nhận</label>
              <input type="password" class="form-control" id="reg_confirm" name="confirm" minlength="6" required>
              <div class="invalid-feedback">Xác nhận mật khẩu chưa khớp.</div>
            </div>
          </div>
          <div class="form-text mt-2">Bằng việc đăng ký, bạn đồng ý với Điều khoản & Chính sách bảo mật.</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-success">Tạo tài khoản</button>
        </div>
      </form>
    </div>
  </div>
</div>

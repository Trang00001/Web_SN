<?php /* Popup Đăng ký (UI + submit cơ bản) */ ?>
<div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content auth-modal">
      <div class="modal-header">
        <h5 class="modal-title">Đăng ký</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <form method="POST" action="/auth/register" data-validate="register" novalidate>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Tên hiển thị</label>
            <input type="text" name="name" class="form-control" required>
            <div class="invalid-feedback">Không được để trống tên.</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
            <div class="invalid-feedback">Email không hợp lệ.</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control" minlength="6" required>
            <div class="invalid-feedback">Mật khẩu tối thiểu 6 ký tự.</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Nhập lại mật khẩu</label>
            <input type="password" name="password_confirm" class="form-control" minlength="6" required>
            <div class="invalid-feedback">Mật khẩu nhập lại chưa khớp.</div>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="agree" required>
            <label class="form-check-label" for="agree">Tôi đồng ý với điều khoản.</label>
            <div class="invalid-feedback">Bạn cần đồng ý điều khoản.</div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success w-100" type="submit">Tạo tài khoản</button>
        </div>
      </form>
    </div>
  </div>
</div>

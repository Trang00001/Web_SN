<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(16)); }
$csrf = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng ký - Social Network</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm rounded-4">
          <div class="card-body p-4">
            <h1 class="h4 mb-4 text-center">Tạo tài khoản</h1>
            <?php if (isset($_SESSION['register_error'])): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['register_error']); unset($_SESSION['register_error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['register_success'])): ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['register_success']); unset($_SESSION['register_success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>
            <form id="formRegister" method="POST" action="/auth/register" novalidate>
              <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf); ?>">
              <div class="mb-3">
                <label for="reg_email" class="form-label">Email</label>
                <input class="form-control" type="email" id="reg_email" name="email" placeholder="name@example.com" required>
              </div>
              <div class="mb-3">
                <label for="reg_username" class="form-label">Tên người dùng</label>
                <input class="form-control" type="text" id="reg_username" name="username" placeholder="vd: nguyenvanA" required minlength="3">
              </div>
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="reg_password" class="form-label">Mật khẩu</label>
                  <input class="form-control" type="password" id="reg_password" name="password" placeholder="••••••" required minlength="6">
                </div>
                <div class="col-md-6">
                  <label for="reg_confirm" class="form-label">Nhập lại mật khẩu</label>
                  <input class="form-control" type="password" id="reg_confirm" name="confirm_password" placeholder="••••••" required minlength="6">
                </div>
              </div>
              <div class="d-grid gap-2 mt-3">
                <button class="btn btn-primary" type="submit">Đăng ký</button>
                <a class="btn btn-outline-secondary" href="/login">Đã có tài khoản</a>
              </div>
                <div id="registerMsg" class="mt-3"></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include __DIR__ . '/../../components/layout/toast.php'; ?>
    <script src="/assets/js/validation.js"></script>
    <script>
      window.attachBasicValidation('#formRegister', { confirm: true });

      document.getElementById('formRegister').addEventListener('submit', async function(e) {
        e.preventDefault();
        e.stopPropagation();
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        
        // Disable submit button to prevent double submission
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.textContent = 'Đang xử lý...';
        }
      
        try {
          const formData = new FormData(form);
          const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          });
          
          // Check if response is JSON
          const contentType = response.headers.get('content-type');
          if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Non-JSON response:', text);
            showErrorToast('Có lỗi xảy ra, vui lòng thử lại');
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.textContent = 'Đăng ký';
            }
            return;
          }
          
          const data = await response.json();
          
          if (data.success) {
            // Hiển thị thông báo thành công
            if (typeof showSuccessToast === 'function') {
              showSuccessToast(data.message);
            } else {
              alert(data.message);
            }
            // Redirect về trang đăng nhập ngay sau khi đăng ký thành công
            if (data.redirect) {
              setTimeout(() => {
                window.location.href = data.redirect;
              }, 800); // Giảm timeout để redirect nhanh hơn
            } else {
              // Nếu không có redirect trong response, redirect về /login
              setTimeout(() => {
                window.location.href = '/login';
              }, 800);
            }
          } else {
            if (typeof showErrorToast === 'function') {
              showErrorToast(data.message || 'Đăng ký thất bại');
            } else {
              alert('Lỗi: ' + (data.message || 'Đăng ký thất bại'));
            }
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.textContent = 'Đăng ký';
            }
          }
        } catch (error) {
          console.error('Registration error:', error);
          if (typeof showErrorToast === 'function') {
            showErrorToast('Có lỗi xảy ra, vui lòng thử lại');
          } else {
            alert('Có lỗi xảy ra: ' + error.message);
          }
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Đăng ký';
          }
        }
      });
    </script>
  </body>
  </html>

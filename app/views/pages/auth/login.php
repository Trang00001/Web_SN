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
  <title>Đăng nhập - Social Network</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm rounded-4">
          <div class="card-body p-4">
            <h1 class="h4 mb-4 text-center">Đăng nhập</h1>
            <form id="formLogin" method="POST" action="<?php echo htmlspecialchars(defined('BASE_URL') ? BASE_URL : ''); ?>/auth/login" novalidate>
              <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf); ?>">
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input class="form-control" type="email" id="email" name="email" placeholder="name@example.com" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input class="form-control" type="password" id="password" name="password" placeholder="••••••" required minlength="6">
              </div>
              <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit">Đăng nhập</button>
                <a class="btn btn-outline-secondary" href="<?php echo htmlspecialchars(defined('BASE_URL') ? BASE_URL : ''); ?>/auth/register">Tạo tài khoản</a>
                <a class="btn btn-link" href="<?php echo htmlspecialchars(defined('BASE_URL') ? BASE_URL : ''); ?>/auth/forgot">Quên mật khẩu?</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <?php include __DIR__ . '/../../components/layout/toast.php'; ?>
  <script>
    document.getElementById('formLogin').addEventListener('submit', function(e) {
      e.preventDefault();
      const form = e.target;
      
      const formData = new FormData(form);
      fetch(form.action, {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showSuccessToast(data.message);
          if (data.redirect) {
            setTimeout(() => {
              window.location.href = data.redirect;
            }, 1500);
          }
        } else {
          showErrorToast(data.message);
        }
      })
      .catch(error => {
        showErrorToast('Có lỗi xảy ra, vui lòng thử lại');
      });
    });
  </script>
</body>
</html>

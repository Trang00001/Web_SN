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
                <a class="btn btn-outline-secondary" href="/auth/login">Đã có tài khoản</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

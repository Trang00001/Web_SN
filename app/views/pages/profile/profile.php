<?php
// app/views/pages/profile/profile.php
ob_start();
?><!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hồ sơ - Social Network</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= $BASE ?>/assets/css/profile.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="profile-page">
  <div class="container py-4">
    <div class="row g-4">
      <div class="col-lg-4">
        <?php
          $name = $name ?? 'Nguyễn Văn A';
          $email = $email ?? 'nguyenvana@example.com';
          $avatar = $avatar ?? 'https://i.pravatar.cc/200';
          include __DIR__ . '/../../components/profile/profile-card.php';
        ?>
        <div class="card mt-3 rounded-4 shadow-sm">
          <div class="card-body">
            <h3 class="h6 text-uppercase text-muted">Thông tin cơ bản</h3>
            <ul class="list-unstyled mb-0 small">
              <li class="py-1"><i class="fa-regular fa-user me-2"></i>Giới tính: <strong>Nam</strong></li>
              <li class="py-1"><i class="fa-regular fa-calendar me-2"></i>Ngày sinh: <strong>01/01/2000</strong></li>
              <li class="py-1"><i class="fa-solid fa-location-dot me-2"></i>Địa điểm: <strong>TP. Hồ Chí Minh</strong></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-8">
        <div class="card rounded-4 shadow-sm">
          <div class="card-body">
            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">Tổng quan</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button" role="tab">Bài viết</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="photos-tab" data-bs-toggle="tab" data-bs-target="#photos" type="button" role="tab">Ảnh</button>
              </li>
            </ul>
            <div class="tab-content pt-3">
              <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <p class="text-muted mb-0">Chào mừng đến trang cá nhân. Đây là phần mô tả ngắn về người dùng.</p>
              </div>
              <div class="tab-pane fade" id="posts" role="tabpanel">
                <div class="alert alert-info">Chưa có bài viết.</div>
              </div>
              <div class="tab-pane fade" id="photos" role="tabpanel">
                <div class="row g-2">
                  <div class="col-4"><div class="ratio ratio-1x1 bg-light rounded"></div></div>
                  <div class="col-4"><div class="ratio ratio-1x1 bg-light rounded"></div></div>
                  <div class="col-4"><div class="ratio ratio-1x1 bg-light rounded"></div></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Lấy nội dung buffer
$content = ob_get_clean();

// Áp dụng layout
require_once __DIR__ . '/../../layouts/main.php';
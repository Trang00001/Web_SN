<?php
/**
 * Trang Profile - MVC Pattern
 * Hiển thị thông tin cá nhân của user
 */

// Load helpers for authentication
require_once __DIR__ . '/../../../../core/Helpers.php';

// Require authentication
require_auth();

// session_start() already called in public/index.php
ob_start();

// Dữ liệu đã được truyền từ Controller
// $name, $email, $username, $avatar, $gender, $birthDate, $hometown, $bio
// $posts, $postCount, $photos

// Format dữ liệu hiển thị
$displayName = $name ?? $username ?? 'User';
$displayEmail = $email ?? '';
$displayAvatar = $avatar ?? '/assets/images/default-avatar.png';
$displayGender = $gender ?? 'Chưa cập nhật';
$displayBirthDate = $birthDate ?? 'Chưa cập nhật';
$displayHometown = $hometown ?? 'Chưa cập nhật';
$displayBio = $bio ?? 'Chào mừng đến trang cá nhân của tôi!';

// Format birth date
if ($displayBirthDate && $displayBirthDate !== 'Chưa cập nhật') {
    $displayBirthDate = date('d/m/Y', strtotime($displayBirthDate));
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($displayName) ?> - TechConnect</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/profile.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="profile-page">
  <div class="container py-4">
    <?php if (!empty($_SESSION['flash_success'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['flash_success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['flash_success']); endif; ?>
    <?php if (!empty($_SESSION['flash_error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['flash_error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['flash_error']); endif; ?>
    <div class="row g-4">
      <!-- Left Sidebar: Profile Card -->
      <div class="col-lg-4">
        <div class="card rounded-4 shadow-sm">
          <div class="card-body text-center">
            <div class="mb-3">
              <?php if ($displayAvatar && $displayAvatar !== '/assets/images/default-avatar.png'): ?>
                <img src="<?= htmlspecialchars($displayAvatar) ?>" 
                     alt="Avatar" 
                     class="rounded-circle"
                     style="width: 120px; height: 120px; object-fit: cover;"
                     onerror="this.src='https://i.pravatar.cc/200'">
              <?php else: ?>
                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                     style="width: 120px; height: 120px;">
                  <span class="text-white fw-bold fs-1">
                    <?= strtoupper(substr($displayName, 0, 1)) ?>
                  </span>
                </div>
              <?php endif; ?>
            </div>
            <h5 class="mb-1"><?= htmlspecialchars($displayName) ?></h5>
            <p class="text-muted small mb-3">
              <i class="fa-regular fa-envelope me-1"></i>
              <?= htmlspecialchars($displayEmail) ?>
            </p>
            <div class="d-flex justify-content-center gap-2">
              <a href="/profile/edit" class="btn btn-sm btn-primary">
                <i class="fas fa-edit me-1"></i>Chỉnh sửa
              </a>
            </div>
          </div>
        </div>
        
        <!-- Thông tin cơ bản -->
        <div class="card mt-3 rounded-4 shadow-sm">
          <div class="card-body">
            <h3 class="h6 text-uppercase text-muted mb-3">Thông tin cơ bản</h3>
            <ul class="list-unstyled mb-0 small">
              <li class="py-2 border-bottom">
                <i class="fa-regular fa-user me-2 text-primary"></i>
                <span class="text-muted">Giới tính:</span>
                <strong class="float-end"><?= htmlspecialchars($displayGender) ?></strong>
              </li>
              <li class="py-2 border-bottom">
                <i class="fa-regular fa-calendar me-2 text-primary"></i>
                <span class="text-muted">Ngày sinh:</span>
                <strong class="float-end"><?= htmlspecialchars($displayBirthDate) ?></strong>
              </li>
              <li class="py-2">
                <i class="fa-solid fa-location-dot me-2 text-primary"></i>
                <span class="text-muted">Địa điểm:</span>
                <strong class="float-end"><?= htmlspecialchars($displayHometown) ?></strong>
              </li>
            </ul>
          </div>
        </div>
        
        <!-- Thống kê -->
        <div class="card mt-3 rounded-4 shadow-sm">
          <div class="card-body">
            <h3 class="h6 text-uppercase text-muted mb-3">Thống kê</h3>
            <div class="row text-center">
              <div class="col-6">
                <h4 class="mb-0 text-primary"><?= $postCount ?? 0 ?></h4>
                <small class="text-muted">Bài viết</small>
              </div>
              <div class="col-6">
                <h4 class="mb-0 text-primary"><?= count($photos ?? []) ?></h4>
                <small class="text-muted">Ảnh</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Right Content: Tabs -->
      <div class="col-lg-8">
        <div class="card rounded-4 shadow-sm">
          <div class="card-body">
            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" 
                        data-bs-target="#overview" type="button" role="tab">
                  <i class="fas fa-info-circle me-1"></i>Tổng quan
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="posts-tab" data-bs-toggle="tab" 
                        data-bs-target="#posts" type="button" role="tab">
                  <i class="fas fa-newspaper me-1"></i>Bài viết (<?= $postCount ?? 0 ?>)
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="photos-tab" data-bs-toggle="tab" 
                        data-bs-target="#photos" type="button" role="tab">
                  <i class="fas fa-image me-1"></i>Ảnh (<?= count($photos ?? []) ?>)
                </button>
              </li>
            </ul>
            
            <div class="tab-content pt-3">
              <!-- Tab Tổng quan -->
              <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="mb-4">
                  <h6 class="text-muted mb-2">Giới thiệu</h6>
                  <p class="mb-0"><?= nl2br(htmlspecialchars($displayBio)) ?></p>
                </div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                      <i class="fas fa-newspaper text-primary me-2"></i>
                      <strong><?= $postCount ?? 0 ?></strong> bài viết
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                      <i class="fas fa-images text-primary me-2"></i>
                      <strong><?= count($photos ?? []) ?></strong> ảnh
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Tab Bài viết -->
              <div class="tab-pane fade" id="posts" role="tabpanel">
                <?php if (!empty($posts) && is_array($posts)): ?>
                  <div class="list-group list-group-flush">
                    <?php foreach ($posts as $post): ?>
                      <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                          <small class="text-muted">
                            <i class="far fa-clock me-1"></i>
                            <?= htmlspecialchars($post['created_at']) ?>
                          </small>
                          <div>
                            <span class="badge bg-light text-dark me-1">
                              <i class="far fa-heart"></i> <?= $post['like_count'] ?>
                            </span>
                            <span class="badge bg-light text-dark">
                              <i class="far fa-comment"></i> <?= $post['comment_count'] ?>
                            </span>
                          </div>
                        </div>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php else: ?>
                  <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Chưa có bài viết nào.
                  </div>
                <?php endif; ?>
              </div>
              
              <!-- Tab Ảnh -->
              <div class="tab-pane fade" id="photos" role="tabpanel">
                <?php if (!empty($photos) && is_array($photos)): ?>
                  <div class="row g-2">
                    <?php foreach ($photos as $photo): ?>
                      <div class="col-4">
                        <div class="ratio ratio-1x1">
                          <img src="<?= htmlspecialchars($photo) ?>" 
                               alt="Photo" 
                               class="img-fluid rounded"
                               style="object-fit: cover; cursor: pointer;"
                               onclick="openImageModal('<?= htmlspecialchars($photo) ?>')"
                               onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23ddd%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%23999%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3ENo Image%3C/text%3E%3C/svg%3E'">
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php else: ?>
                  <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Chưa có ảnh nào.
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Image Modal -->
  <div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content bg-dark">
        <div class="modal-header border-0">
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-0">
          <img id="modalImage" src="" class="img-fluid" alt="Enlarged image">
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  function openImageModal(imageSrc) {
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    document.getElementById('modalImage').src = imageSrc;
    modal.show();
  }
  </script>
</body>
</html>
<?php
// Lấy nội dung buffer
$content = ob_get_clean();

// Áp dụng layout
require_once __DIR__ . '/../../layouts/main.php';

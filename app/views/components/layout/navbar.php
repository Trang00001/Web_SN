<head>
  <link rel="stylesheet" href="/assets/css/notification.css">
</head>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">

    <!-- Logo --> 
    <a class="navbar-brand" href="/">
      <i class="fa-brands fa-instagram"></i> MySocial
    </a>

    <!-- Toggle khi mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">

      <!-- Menu trái -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown mx-1">
          <a class="nav-link active dropdown-toggle" href="/home" id="homeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-house"></i>
            <span class="d-none d-lg-inline">Home</span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="homeDropdown">
            <li><a class="dropdown-item" href="#" id="createCategoryBtn"><i class="fas fa-plus me-2"></i>Tạo danh mục</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/home"><i class="fas fa-home me-2"></i>Tất cả</a></li>
            <li><a class="dropdown-item" href="/home?saved=1"><i class="fas fa-bookmark me-2"></i>Đã lưu</a></li>
            <li><hr class="dropdown-divider"></li>
            <?php
            try {
              require_once __DIR__ . '/../../../models/PostCategory.php';
              $categoryModel = new PostCategory();
              $categories = $categoryModel->getAll();
              if ($categories && is_array($categories)) {
                foreach ($categories as $cat) {
                  echo '<li><a class="dropdown-item" href="/home?category=' . $cat['CategoryID'] . '">';
                  echo htmlspecialchars($cat['CategoryName']);
                  echo '</a></li>';
                }
              }
            } catch (Exception $e) {
              error_log("Navbar category error: " . $e->getMessage());
            }
            ?>
          </ul>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" href="/friends">
            <i class="fa-solid fa-user-friends"></i>
            <span class="d-none d-lg-inline">Bạn bè</span>
          </a>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" href="/messages">
            <i class="fa-solid fa-comments"></i>
            <span class="d-none d-lg-inline">Tin nhắn</span>
          </a>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" href="/profile">
            <i class="fa-solid fa-user"></i>
            <span class="d-none d-lg-inline">Trang cá nhân</span>
          </a>
        </li>
      </ul>

      <!-- Thanh tìm kiếm -->
      <?php include __DIR__ . '/search-bar.php'; ?>
      
      <!-- Thông báo -->
      <div class="dropdown position-relative ms-3">
        <button id="notifBtn" class="btn btn-outline-primary position-relative">
          <i class="fa-solid fa-bell"></i>
          <span id="notifCount" class="badge bg-danger position-absolute top-0 start-100 translate-middle"></span>
        </button>

        <div id="notifList" class="notif-list dropdown-menu p-2 shadow">
        <!-- JS sẽ render thông báo vào đây -->
        </div>
      </div>


      <!-- Menu phải -->
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item mx-1">
          <a class="nav-link" href="#" id="settingsBtn" data-bs-toggle="modal" data-bs-target="#settingsModal">
            <i class="fa-solid fa-gear"></i>
            <span class="d-none d-lg-inline">Cài đặt</span>
          </a>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" href="/auth/logout" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">
            <i class="fa-solid fa-sign-out-alt"></i>
            <span class="d-none d-lg-inline">Đăng xuất</span>
          </a>
        </li>
      </ul>

    </div>
  </div>
</nav>

<!-- Modal Tạo Danh Mục -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tạo danh mục mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" id="categoryNameInput" class="form-control" placeholder="Tên danh mục" maxlength="50">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary btn-sm" id="saveCategoryBtn">Tạo</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Cài đặt -->
<div class="modal fade" id="settingsModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa-solid fa-gear me-2"></i>Cài đặt</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Theme Selection -->
        <div class="mb-4">
          <h6 class="mb-3"><i class="fa-solid fa-palette me-2"></i>Giao diện</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Chủ đề</label>
              <select id="themeSelect" class="form-select">
                <option value="light">Sáng</option>
                <option value="dark">Tối</option>
                <option value="auto">Tự động (theo hệ thống)</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Màu chủ đạo</label>
              <select id="colorSchemeSelect" class="form-select">
                <option value="default">Mặc định (Tím)</option>
                <option value="blue">Xanh dương</option>
                <option value="green">Xanh lá</option>
                <option value="red">Đỏ</option>
                <option value="orange">Cam</option>
              </select>
            </div>
          </div>
        </div>

        <hr>

        <!-- UI Preferences -->
        <div class="mb-4">
          <h6 class="mb-3"><i class="fa-solid fa-sliders me-2"></i>Tùy chọn hiển thị</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Kích thước chữ</label>
              <select id="fontSizeSelect" class="form-select">
                <option value="small">Nhỏ</option>
                <option value="medium" selected>Vừa</option>
                <option value="large">Lớn</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Mật độ hiển thị</label>
              <select id="densitySelect" class="form-select">
                <option value="compact">Chật</option>
                <option value="comfortable" selected>Vừa</option>
                <option value="spacious">Rộng</option>
              </select>
            </div>
          </div>
        </div>

        <hr>

        <!-- Other Settings -->
        <div class="mb-3">
          <h6 class="mb-3"><i class="fa-solid fa-bell me-2"></i>Thông báo</h6>
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="notifSoundSwitch" checked>
            <label class="form-check-label" for="notifSoundSwitch">
              Âm thanh thông báo
            </label>
          </div>
          <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" id="notifDesktopSwitch">
            <label class="form-check-label" for="notifDesktopSwitch">
              Thông báo trên desktop
            </label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-primary" id="saveSettingsBtn">
          <i class="fa-solid fa-save me-2"></i>Lưu cài đặt
        </button>
        <button type="button" class="btn btn-outline-secondary" id="resetSettingsBtn">
          <i class="fa-solid fa-rotate-left me-2"></i>Đặt lại
        </button>
      </div>
    </div>
  </div>
</div>

<script>
// Xử lý tạo danh mục
document.addEventListener('DOMContentLoaded', function() {
  const createBtn = document.getElementById('createCategoryBtn');
  const modal = new bootstrap.Modal(document.getElementById('createCategoryModal'));
  const saveBtn = document.getElementById('saveCategoryBtn');
  const input = document.getElementById('categoryNameInput');

  if (createBtn) {
    createBtn.addEventListener('click', function(e) {
      e.preventDefault();
      input.value = '';
      modal.show();
    });
  }

  if (saveBtn) {
    saveBtn.addEventListener('click', async function() {
      const name = input.value.trim();
      if (!name) {
        alert('Vui lòng nhập tên danh mục');
        return;
      }

      try {
        const response = await fetch('/api/categories/create.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name: name })
        });

        const data = await response.json();
        if (data.success) {
          modal.hide();
          location.reload();
        } else {
          alert(data.message || 'Lỗi khi tạo danh mục');
        }
      } catch (error) {
        alert('Lỗi kết nối: ' + error.message);
      }
    });
  }
});
</script>
<script src="/assets/js/notifications.js"></script>
<script src="/assets/js/settings.js"></script>

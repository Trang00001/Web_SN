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

      <!-- Menu phải -->
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
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
<?php
/**
 * Modern Navigation Bar Component
 */

$current_user = $_SESSION['username'] ?? 'Guest';
?>

<nav class="navbar navbar-expand-lg sticky-top" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(102, 126, 234, 0.1); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);">
    <div class="container-fluid">
        <!-- Logo/Brand -->
        <a class="navbar-brand fw-bold" href="#" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 1.5rem; text-decoration: none;">
            <i class="fas fa-code me-2"></i>
            TechConnect
        </a>

        <!-- Search Bar (Center) -->
        <div class="d-flex align-items-center flex-grow-1 justify-content-center">
            <div class="input-group" style="max-width: 500px;">
                <span class="input-group-text bg-light border-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" 
                       class="form-control border-0 bg-light" 
                       placeholder="Tìm kiếm bài viết, người dùng, hashtag..."
                       style="border-radius: 0 25px 25px 0;">
            </div>
        </div>

        <!-- User Actions -->
        <div class="d-flex align-items-center">
            <!-- Notifications -->
            <button class="btn btn-outline-primary rounded-pill me-3 position-relative">
                <i class="fas fa-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    3
                </span>
            </button>

            <!-- Messages -->
            <button class="btn btn-outline-primary rounded-pill me-3 position-relative">
                <i class="fas fa-envelope"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                    5
                </span>
            </button>

            <!-- User Dropdown -->
            <div class="dropdown">
                <button class="btn btn-primary rounded-pill dropdown-toggle d-flex align-items-center" 
                        data-bs-toggle="dropdown">
                    <div class="me-2" style="width: 30px; height: 30px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                        <?= strtoupper(substr($current_user, 0, 1)) ?>
                    </div>
                    <?= htmlspecialchars($current_user) ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user me-2"></i>Hồ sơ cá nhân
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cog me-2"></i>Cài đặt
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-moon me-2"></i>Chế độ tối
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
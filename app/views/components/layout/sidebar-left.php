<?php
/**
 * Left Sidebar Component
 * Chứa user profile và menu navigation
 */

$currentUser = $_SESSION['username'] ?? 'User';
$userId = $_SESSION['user_id'] ?? 1;
?>

<!-- User Profile Section -->
<div class="tech-card user-profile">
    <div class="user-avatar-lg mx-auto mb-3">
        <?= strtoupper(substr($currentUser, 0, 1)) ?>
    </div>
    <h5 class="text-center mb-2"><?= htmlspecialchars($currentUser) ?></h5>
    <p class="text-center text-muted mb-3">@<?= strtolower(str_replace(' ', '', $currentUser)) ?></p>
    
    <!-- User Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-number">127</span>
            <small>Bài viết</small>
        </div>
        <div class="stat-card">
            <span class="stat-number">2.5K</span>
            <small>Bạn bè</small>
        </div>
    </div>
</div>

<!-- Navigation Menu -->
<div class="tech-card">
    <h6 class="mb-3 fw-bold">Menu chính</h6>
    
    <a href="/home" class="menu-item">
        <i class="fas fa-home"></i>
        <span>Trang chủ</span>
    </a>
    
    <a href="/friends" class="menu-item">
        <i class="fas fa-users"></i>
        <span>Bạn bè</span>
    </a>
    
    <a href="/messages" class="menu-item">
        <i class="fas fa-envelope"></i>
        <span>Tin nhắn</span>
    </a>
    
    <a href="/notifications" class="menu-item">
        <i class="fas fa-bell"></i>
        <span>Thông báo</span>
    </a>
    
    <a href="/search" class="menu-item">
        <i class="fas fa-compass"></i>
        <span>Khám phá</span>
    </a>
    
    <a href="#" class="menu-item">
        <i class="fas fa-bookmark"></i>
        <span>Đã lưu</span>
    </a>
    
    <a href="/profile" class="menu-item">
        <i class="fas fa-cog"></i>
        <span>Cài đặt</span>
    </a>
</div>
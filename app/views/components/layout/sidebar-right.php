<?php
/**
 * Right Sidebar Component
 * Chứa online friends và trending topics
 */
?>

<!-- Online Friends -->
<div class="tech-card">
    <h6 class="mb-3 fw-bold">
        <i class="fas fa-circle text-success me-2"></i>
        Bạn bè đang online
    </h6>
    
    <div class="friend-item">
        <div class="online-status me-3">
            <div class="user-avatar-sm bg-success">D</div>
        </div>
        <div>
            <div class="fw-medium">David Wilson</div>
            <small class="text-muted">Đang hoạt động</small>
        </div>
    </div>
    
    <div class="friend-item">
        <div class="online-status me-3">
            <div class="user-avatar-sm bg-warning">E</div>
        </div>
        <div>
            <div class="fw-medium">Emma Brown</div>
            <small class="text-muted">5 phút trước</small>
        </div>
    </div>
    
    <div class="friend-item">
        <div class="online-status me-3">
            <div class="user-avatar-sm bg-info">F</div>
        </div>
        <div>
            <div class="fw-medium">Frank Miller</div>
            <small class="text-muted">Đang hoạt động</small>
        </div>
    </div>
    
    <div class="friend-item">
        <div class="online-status me-3">
            <div class="user-avatar-sm bg-danger">G</div>
        </div>
        <div>
            <div class="fw-medium">Grace Lee</div>
            <small class="text-muted">10 phút trước</small>
        </div>
    </div>
    
    <button class="btn btn-outline-primary btn-sm w-100 mt-2">
        Xem tất cả
    </button>
</div>

<!-- Trending Topics -->
<div class="tech-card">
    <h6 class="mb-3 fw-bold">
        <i class="fas fa-fire text-warning me-2"></i>
        Xu hướng
    </h6>
    
    <div class="trending-item">
        <div class="trending-icon">
            <i class="fas fa-hashtag"></i>
        </div>
        <div>
            <div class="fw-medium">#TechTalk2024</div>
            <small class="text-muted">15.2K bài viết</small>
        </div>
    </div>
    
    <div class="trending-item">
        <div class="trending-icon">
            <i class="fas fa-code"></i>
        </div>
        <div>
            <div class="fw-medium">#WebDevelopment</div>
            <small class="text-muted">8.7K bài viết</small>
        </div>
    </div>
    
    <div class="trending-item">
        <div class="trending-icon">
            <i class="fas fa-robot"></i>
        </div>
        <div>
            <div class="fw-medium">#AI_Innovation</div>
            <small class="text-muted">6.3K bài viết</small>
        </div>
    </div>
    
    <div class="trending-item">
        <div class="trending-icon">
            <i class="fas fa-mobile-alt"></i>
        </div>
        <div>
            <div class="fw-medium">#MobileFirst</div>
            <small class="text-muted">4.1K bài viết</small>
        </div>
    </div>
    
    <button class="btn btn-outline-primary btn-sm w-100 mt-2">
        Xem thêm xu hướng
    </button>
</div>

<!-- Suggested Pages -->
<div class="tech-card">
    <h6 class="mb-3 fw-bold">
        <i class="fas fa-star text-warning me-2"></i>
        Trang đề xuất
    </h6>
    
    <div class="trending-item">
        <div class="trending-icon bg-primary">
            <i class="fas fa-laptop-code"></i>
        </div>
        <div class="flex-grow-1">
            <div class="fw-medium">TechNews VN</div>
            <small class="text-muted">125K người theo dõi</small>
        </div>
        <button class="btn btn-outline-primary btn-sm">Follow</button>
    </div>
    
    <div class="trending-item">
        <div class="trending-icon bg-success">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="flex-grow-1">
            <div class="fw-medium">CodeLearn</div>
            <small class="text-muted">89K người theo dõi</small>
        </div>
        <button class="btn btn-outline-primary btn-sm">Follow</button>
    </div>
</div>
<?php
/**
 * Create Post Component
 * Component tạo bài viết mới
 */

$currentUser = $_SESSION['username'] ?? 'User';
?>

<div class="create-post-card">
    <div class="d-flex align-items-center mb-4">
        <div class="user-avatar me-3">
            <?= strtoupper(substr($currentUser, 0, 1)) ?>
        </div>
        <div class="flex-grow-1">
            <input type="text" 
                   class="form-control create-post-input" 
                   placeholder="Bạn đang nghĩ gì, <?= htmlspecialchars($currentUser) ?>?"
                   data-bs-toggle="modal" 
                   data-bs-target="#createPostModal"
                   readonly>
        </div>
    </div>
    
    <div class="d-flex justify-content-around">
        <button class="create-post-btn" 
                data-bs-toggle="modal" 
                data-bs-target="#createPostModal">
            <i class="fas fa-image me-2"></i>
            Ảnh/Video
        </button>
        <button class="create-post-btn" 
                data-bs-toggle="modal" 
                data-bs-target="#createPostModal">
            <i class="fas fa-smile me-2"></i>
            Cảm xúc
        </button>
        <button class="create-post-btn" 
                data-bs-toggle="modal" 
                data-bs-target="#createPostModal">
            <i class="fas fa-video me-2"></i>
            Live Stream
        </button>
    </div>
</div>

<!-- Modal được define trong home.php -->
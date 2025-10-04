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
                   onclick="showCreatePostModal()"
                   readonly>
        </div>
    </div>
    
    <div class="d-flex justify-content-around">
        <button class="create-post-btn" onclick="showCreatePostModal('photo')">
            <i class="fas fa-image me-2"></i>
            Ảnh/Video
        </button>
        <button class="create-post-btn" onclick="showCreatePostModal('feeling')">
            <i class="fas fa-smile me-2"></i>
            Cảm xúc
        </button>
        <button class="create-post-btn" onclick="showCreatePostModal('live')">
            <i class="fas fa-video me-2"></i>
            Live Stream
        </button>
    </div>
</div>

<!-- Create Post Modal -->
<div class="modal fade" id="createPostModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Tạo bài viết mới
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
                <div class="modal-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="user-avatar me-3">
                        <?= strtoupper(substr($currentUser, 0, 1)) ?>
                    </div>
                    <div>
                        <h6 class="mb-0 text-dark"><?= htmlspecialchars($currentUser) ?></h6>
                        <small class="text-muted">
                            <i class="fas fa-globe me-1"></i>
                            Công khai
                        </small>
                    </div>
                </div>
                
                <textarea class="form-control mb-3" 
                          rows="4" 
                          placeholder="Bạn đang nghĩ gì?"
                          style="border: 1px solid #ddd; background: #f8f9fa; color: #333;"></textarea>                <div class="row mb-3">
                    <div class="col-6">
                        <div class="border rounded p-3 text-center">
                            <i class="fas fa-image fa-2x text-success mb-2"></i>
                            <div>Thêm ảnh/video</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3 text-center">
                            <i class="fas fa-map-marker-alt fa-2x text-danger mb-2"></i>
                            <div>Thêm địa điểm</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" onclick="createPost()">
                    <i class="fas fa-paper-plane me-2"></i>
                    Đăng bài
                </button>
            </div>
        </div>
    </div>
</div>
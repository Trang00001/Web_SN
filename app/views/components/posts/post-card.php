<?php
/**
 * Component hiển thị 1 bài viết - CLEAN VERSION
 * Chỉ chứa HTML/PHP, JavaScript đã tách ra posts.js
 */

if (!isset($post)) return;

$post_id = $post['post_id'];
$username = $post['username'] ?? 'Unknown';
$content = $post['content'] ?? '';
$media_url = $post['media_url'] ?? null;
$like_count = $post['like_count'] ?? 0;
$comment_count = $post['comment_count'] ?? 0;
$created_at = $post['created_at'] ?? 'Vừa xong';
$show_comments = $show_comments ?? false;

// Kiểm tra user đã like chưa (mock data - fixed state)
$user_liked = ($post_id % 2 == 0); // Chẵn = liked, lẻ = chưa like
?>

<div class="post-card mb-4" data-post-id="<?= $post_id ?>">
    <!-- Post Header -->
    <div class="post-header">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                     style="width: 40px; height: 40px;">
                    <span class="text-white fw-bold"><?= strtoupper(substr($username, 0, 1)) ?></span>
                </div>
                <div>
                    <h6 class="mb-0"><?= htmlspecialchars($username) ?></h6>
                    <small class="text-muted"><?= htmlspecialchars($created_at) ?></small>
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-link text-muted p-1" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-bookmark me-2"></i>Lưu bài viết</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-flag me-2"></i>Báo cáo</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Post Content -->
    <div class="post-content">
        <?php if ($content): ?>
        <p class="post-text"><?= nl2br(htmlspecialchars($content)) ?></p>
        <?php endif; ?>
        
        <?php if ($media_url): ?>
        <div class="post-media">
            <img src="<?= htmlspecialchars($media_url) ?>" 
                 class="img-fluid rounded" 
                 alt="Post media"
                 onclick="openImageModal('<?= htmlspecialchars($media_url) ?>')">
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Post Stats -->
    <div class="post-stats">
        <div class="like-reactions">
            <span class="like-count"><?= number_format($like_count) ?> lượt thích</span>
        </div>
        <span class="comment-count"><?= number_format($comment_count) ?> bình luận</span>
    </div>
    
    <!-- Post Actions -->
    <div class="post-actions">
        <button class="action-btn like-btn <?= $user_liked ? 'liked' : '' ?>" 
                data-post-id="<?= $post_id ?>">
            <i class="<?= $user_liked ? 'fas' : 'far' ?> fa-heart"></i>
            <span><?= $user_liked ? 'Đã thích' : 'Thích' ?></span>
        </button>
        <button class="action-btn comment-btn" data-post-id="<?= $post_id ?>">
            <i class="fas fa-comment"></i>
            <span>Bình luận</span>
        </button>
        <button class="action-btn share-btn" data-post-id="<?= $post_id ?>">
            <i class="fas fa-share"></i>
            <span>Chia sẻ</span>
        </button>
    </div>
    
    <!-- Comments Section -->
    <div class="comments-section <?= $show_comments ? 'show' : '' ?>" id="comments-<?= $post_id ?>">
        <div class="comments-list">
            <!-- Mock comments for demo -->
            <div class="comment-item">
                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                     style="width: 32px; height: 32px;">
                    <span class="text-white small fw-bold">J</span>
                </div>
                <div class="comment-content">
                    <div class="bg-light rounded p-2">
                        <small class="fw-bold">John Doe</small>
                        <div>Bài viết hay quá! 👍</div>
                    </div>
                    <small class="text-muted">2 phút trước</small>
                </div>
            </div>
        </div>
        
        <!-- Comment Input -->
        <div class="d-flex mt-3">
            <input type="text" class="comment-input me-2" 
                   placeholder="Viết bình luận..." 
                   data-post-id="<?= $post_id ?>">
            <button class="btn btn-primary btn-sm" onclick="submitComment(<?= $post_id ?>)">Gửi</button>
        </div>
    </div>
</div>
<?php
/**
 * Component hiển thị 1 bài viết - CLEAN VERSION
 * Chỉ chứa HTML/PHP, JavaScript đã tách ra posts.js
 */

// Set timezone to match database
date_default_timezone_set('Asia/Ho_Chi_Minh');

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

// Load real comments từ database
$real_comments = [];
try {
    require_once __DIR__ . '/../../../models/Comment.php';
    $commentModel = new Comment($post_id, 0); // accountID không quan trọng cho getByPost()
    $commentsFromDB = $commentModel->getByPost();
    
    if ($commentsFromDB && is_array($commentsFromDB)) {
        foreach ($commentsFromDB as $commentRow) {
            // CommentTime là field name từ stored procedure
            $commentTimeValue = $commentRow['CommentTime'] ?? $commentRow['CreatedAt'] ?? null;
            
            if (!$commentTimeValue) {
                $commentTimeAgo = 'Vừa xong';
            } else {
                $commentCreatedAt = strtotime($commentTimeValue);
                $now = time();
                $diff = $now - $commentCreatedAt;
                
                $minutes = floor(abs($diff) / 60);
                
                // Nếu diff âm (timestamp trong tương lai), coi như "Vừa xong"
                if ($diff < 0 || $diff < 60) {
                    $commentTimeAgo = 'Vừa xong';
                } elseif ($diff < 3600) {
                    $commentTimeAgo = $minutes . ' phút trước';  // "5 phút trước"
                } elseif ($diff < 86400) {
                    $hours = floor($diff / 3600);
                    $commentTimeAgo = $hours . ' giờ trước';
                } elseif ($diff < 604800) {
                    $days = floor($diff / 86400);
                    $commentTimeAgo = $days . ' ngày trước';
                } else {
                    // Hiển thị ngày tháng nếu > 7 ngày
                    $commentTimeAgo = date('d/m/Y', $commentCreatedAt);
                }
            }
            
            $real_comments[] = [
                'username' => $commentRow['Username'] ?? 'Anonymous',
                'content' => $commentRow['Content'] ?? '',
                'created_at' => $commentTimeAgo
            ];
        }
    }
} catch (Exception $e) {
    error_log("Error loading comments for post {$post_id}: " . $e->getMessage());
}
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
                 onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22300%22><rect fill=%22%23ddd%22 width=%22400%22 height=%22300%22/><text x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%23999%22>Image not found</text></svg>'; this.style.cursor='not-allowed';"
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
            <?php if (!empty($real_comments)): ?>
                <?php foreach ($real_comments as $comment): ?>
                    <div class="comment-item">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                             style="width: 32px; height: 32px;">
                            <span class="text-white small fw-bold"><?= strtoupper(substr($comment['username'], 0, 1)) ?></span>
                        </div>
                        <div class="comment-content">
                            <div class="bg-light rounded p-2">
                                <small class="fw-bold text-primary"><?= htmlspecialchars($comment['username']) ?></small>
                                <div><?= htmlspecialchars($comment['content']) ?></div>
                            </div>
                            <small class="text-muted"><?= $comment['created_at'] ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Không có comment nào -->
                <small class="text-muted">Chưa có bình luận nào. Hãy là người đầu tiên!</small>
            <?php endif; ?>
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
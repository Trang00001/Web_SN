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

// Kiểm tra user đã like chưa (từ database)
$user_liked = $post['user_liked'] ?? false;

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

// Get category info
$category_id = $post['category_id'] ?? 1;
$category_name = $post['category_name'] ?? 'Cuộc sống';

// Map category to color
$category_colors = [
    1 => 'primary',     // Life - Blue
    2 => 'success',     // Study - Green  
    3 => 'danger'       // Entertainment - Red/Purple
];
$category_color = $category_colors[$category_id] ?? 'secondary';

// Get images for carousel
$images = $post['images'] ?? [];
$imageCount = count($images);
$hasMultipleImages = $imageCount > 1;
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
        
        <!-- Category Badge -->
        <div class="mt-2">
            <span class="badge bg-<?= $category_color ?> text-white px-3 py-1" style="font-size: 0.75rem;">
                <i class="fas fa-tag me-1"></i><?= htmlspecialchars($category_name) ?>
            </span>
        </div>
    </div>
    
    <!-- Post Content -->
    <div class="post-content">
        <?php if ($content): ?>
        <p class="post-text"><?= nl2br(htmlspecialchars($content)) ?></p>
        <?php endif; ?>
        
        <?php if ($imageCount > 0): ?>
        <!-- Image Carousel -->
        <div class="post-carousel-container" data-post-id="<?= $post_id ?>">
            <?php if ($hasMultipleImages): ?>
            <!-- Navigation Arrows (inside carousel) -->
            <button class="carousel-nav carousel-nav-prev" onclick="postCarousel.prev(<?= $post_id ?>); event.stopPropagation();">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="carousel-nav carousel-nav-next" onclick="postCarousel.next(<?= $post_id ?>); event.stopPropagation();">
                <i class="fas fa-chevron-right"></i>
            </button>
            <?php endif; ?>

            <div class="post-carousel">
                <?php foreach ($images as $index => $image): ?>
                <div class="carousel-slide <?= $index === 0 ? 'active' : '' ?>">
                    <img 
                        src="<?= htmlspecialchars($image['url']) ?>" 
                        class="img-fluid w-100" 
                        alt="Post image <?= $index + 1 ?>"
                        onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22300%22%3E%3Crect fill=%22%23ddd%22 width=%22400%22 height=%22300%22/%3E%3Ctext fill=%22%23999%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3EImage not found%3C/text%3E%3C/svg%3E'"
                    >
                </div>
                <?php endforeach; ?>
            </div>

            <?php if ($hasMultipleImages): ?>
            <!-- Dots Indicator -->
            <div class="carousel-indicators">
                <?php for ($i = 0; $i < $imageCount; $i++): ?>
                <span class="dot <?= $i === 0 ? 'active' : '' ?>" 
                      onclick="postCarousel.goTo(<?= $post_id ?>, <?= $i ?>); event.stopPropagation();"></span>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Post Stats -->
    <div class="post-stats">
        <div class="like-reactions">
            <span class="like-count"><?= number_format($like_count) ?> lượt thích</span>
        </div>
        <span class="comment-count"><?= count($real_comments) ?> bình luận</span>
    </div>
    
    <!-- Post Actions -->
    <div class="post-actions">
        <button class="action-btn like-btn <?= $user_liked ? 'liked' : '' ?>" 
                data-post-id="<?= $post_id ?>">
            <i class="<?= $user_liked ? 'fas' : 'far' ?> fa-heart"></i>
            <span><?= $user_liked ? 'Đã thích' : 'Thích' ?></span>
        </button>
        <button class="action-btn comment-btn" data-post-id="<?= $post_id ?>" onclick="openPostDetail(<?= $post_id ?>)">
            <i class="fas fa-comment"></i>
            <span>Bình luận</span>
        </button>
        <button class="action-btn share-btn" data-post-id="<?= $post_id ?>">
            <i class="fas fa-share"></i>
            <span>Chia sẻ</span>
        </button>
    </div>
</div>
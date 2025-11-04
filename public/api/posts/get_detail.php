<?php
/**
 * API: Get Post Detail
 * Trả về HTML cho modal post detail
 */

header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Auto-login for testing
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'Alice';
}

// Get post_id (accept both 'id' and 'post_id' parameter)
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);

if ($post_id <= 0) {
    echo '<div class="alert alert-danger m-4">Post không tồn tại</div>';
    exit();
}

// Load PostController
require_once __DIR__ . '/../../../app/controllers/PostController.php';
$postController = new PostController();

// Lấy userId từ session
$userId = $_SESSION['user_id'] ?? null;

// Get single post
$posts = $postController->getAllPosts($userId);
$currentPost = null;
foreach ($posts as $post) {
    if ($post['post_id'] == $post_id) {
        $currentPost = $post;
        break;
    }
}

if (!$currentPost) {
    echo '<div class="alert alert-danger m-4">Không tìm thấy bài viết</div>';
    exit();
}

// Load images
require_once __DIR__ . '/../../../app/models/Image.php';
$imageModel = new Image($currentPost['post_id'], '');
$currentPost['images'] = $imageModel->getByPostId($currentPost['post_id']);

// Load comments
require_once __DIR__ . '/../../../app/models/Comment.php';
$commentModel = new Comment($currentPost['post_id'], 0);
$commentsFromDB = $commentModel->getByPost();

// Format comments
$comments = [];
if ($commentsFromDB && is_array($commentsFromDB)) {
    foreach ($commentsFromDB as $commentRow) {
        $commentTimeValue = $commentRow['CommentTime'] ?? $commentRow['CreatedAt'] ?? null;
        
        if (!$commentTimeValue) {
            $commentTimeAgo = 'Vừa xong';
        } else {
            $commentCreatedAt = strtotime($commentTimeValue);
            $now = time();
            $diff = $now - $commentCreatedAt;
            
            if ($diff < 0 || $diff < 60) {
                $commentTimeAgo = 'Vừa xong';
            } elseif ($diff < 3600) {
                $minutes = floor($diff / 60);
                $commentTimeAgo = $minutes . ' phút trước';
            } elseif ($diff < 86400) {
                $hours = floor($diff / 3600);
                $commentTimeAgo = $hours . ' giờ trước';
            } elseif ($diff < 604800) {
                $days = floor($diff / 86400);
                $commentTimeAgo = $days . ' ngày trước';
            } else {
                $commentTimeAgo = date('d/m/Y', $commentCreatedAt);
            }
        }
        
        $comments[] = [
            'username' => $commentRow['Username'] ?? 'Anonymous',
            'content' => $commentRow['Content'] ?? '',
            'created_at' => $commentTimeAgo
        ];
    }
}

$images = $currentPost['images'] ?? [];
$imageCount = count($images);
$hasMultipleImages = $imageCount > 1;
?>

<!-- Post Header -->
<div class="post-detail-header">
    <div class="d-flex align-items-center">
        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
             style="width: 50px; height: 50px;">
            <span class="text-white fw-bold fs-5">
                <?= strtoupper(substr($currentPost['username'], 0, 1)) ?>
            </span>
        </div>
        <div>
            <h5 class="mb-0"><?= htmlspecialchars($currentPost['username']) ?></h5>
            <small class="text-muted"><?= htmlspecialchars($currentPost['created_at']) ?></small>
        </div>
    </div>
</div>

<!-- Post Content -->
<div class="post-detail-content">
    <p class="mb-0"><?= nl2br(htmlspecialchars($currentPost['content'])) ?></p>
</div>

<!-- Post Images (Carousel) -->
<?php if ($imageCount > 0): ?>
<div class="post-detail-images">
    <div class="post-carousel-container post-carousel-modal" data-post-id="modal-<?= $currentPost['post_id'] ?>">
        <?php if ($hasMultipleImages): ?>
        <button class="carousel-nav carousel-nav-prev" onclick="postCarousel.prev('modal-<?= $currentPost['post_id'] ?>'); event.stopPropagation();">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="carousel-nav carousel-nav-next" onclick="postCarousel.next('modal-<?= $currentPost['post_id'] ?>'); event.stopPropagation();">
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
        <div class="carousel-indicators">
            <?php for ($i = 0; $i < $imageCount; $i++): ?>
            <span class="dot <?= $i === 0 ? 'active' : '' ?>" 
                  onclick="postCarousel.goTo('modal-<?= $currentPost['post_id'] ?>', <?= $i ?>); event.stopPropagation();"></span>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Post Stats -->
<div class="post-detail-stats">
    <div class="d-flex justify-content-between">
        <span class="like-count"><i class="fas fa-heart text-danger"></i> <?= $currentPost['like_count'] ?> lượt thích</span>
        <span class="comment-count"><?= count($comments) ?> bình luận</span>
    </div>
</div>

<!-- Post Actions -->
<div class="post-detail-actions">
    <div class="d-flex justify-content-around py-2">
        <button class="btn btn-link text-decoration-none post-action like-btn <?= $currentPost['user_liked'] ? 'liked' : '' ?>" 
                data-post-id="<?= $currentPost['post_id'] ?>"
                data-action="like">
            <i class="<?= $currentPost['user_liked'] ? 'fas' : 'far' ?> fa-heart"></i> 
            <span class="like-text"><?= $currentPost['user_liked'] ? 'Bỏ thích' : 'Thích' ?></span>
        </button>
        <button class="btn btn-link text-decoration-none post-action share-btn" 
                data-post-id="<?= $currentPost['post_id'] ?>"
                data-action="share">
            <i class="far fa-share-square"></i> Chia sẻ
        </button>
    </div>
</div>

<!-- Comments Section -->
<div class="post-detail-comments">
    <?php if (empty($comments)): ?>
        <p class="text-muted text-center py-4">
            <i class="far fa-comment-dots fa-2x mb-2 d-block"></i>
            Chưa có bình luận nào. Hãy là người đầu tiên bình luận!
        </p>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
        <div class="comment-item">
            <div class="d-flex gap-2">
                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                     style="width: 36px; height: 36px; flex-shrink: 0;">
                    <span class="text-white fw-bold small">
                        <?= strtoupper(substr($comment['username'], 0, 1)) ?>
                    </span>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h6 class="mb-0 small fw-bold"><?= htmlspecialchars($comment['username']) ?></h6>
                        <small class="text-muted"><?= $comment['created_at'] ?></small>
                    </div>
                    <p class="mb-0 small"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Comment Form -->
<div class="comment-form">
    <form class="comment-form-submit" data-post-id="<?= $currentPost['post_id'] ?>">
        <div class="d-flex gap-2 align-items-start">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                 style="width: 40px; height: 40px; flex-shrink: 0;">
                <span class="text-white fw-bold">
                    <?= strtoupper(substr($_SESSION['username'] ?? $_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                </span>
            </div>
            <div class="flex-grow-1">
                <textarea 
                    class="form-control comment-textarea" 
                    rows="2" 
                    placeholder="Viết bình luận..."
                    name="comment_content"
                    required
                ></textarea>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </form>
</div>

<script>
// Re-initialize carousel for modal
if (typeof postCarousel !== 'undefined') {
    setTimeout(() => {
        postCarousel.initTouchSupport();
    }, 100);
}
</script>

<?php
/**
 * Chi tiết bài viết - Post Detail Page
 * Hiển thị đầy đủ post + comments
 */

// Load helpers for authentication
require_once __DIR__ . '/../../../../core/Helpers.php';

// Require authentication
require_auth();

// session_start() already called in public/index.php
ob_start();

// Get post_id from URL
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($post_id <= 0) {
    header('Location: /home');
    exit();
}

// Load PostController
require_once __DIR__ . '/../../../controllers/PostController.php';
$postController = new PostController();

// Get single post
$posts = $postController->getAllPosts();
$currentPost = null;
foreach ($posts as $post) {
    if ($post['post_id'] == $post_id) {
        $currentPost = $post;
        break;
    }
}

if (!$currentPost) {
    header('Location: home.php');
    exit();
}

// Load images for this post
require_once __DIR__ . '/../../../models/Image.php';
$imageModel = new Image($currentPost['post_id'], '');
$currentPost['images'] = $imageModel->getByPostId($currentPost['post_id']);

// Load comments
require_once __DIR__ . '/../../../models/Comment.php';
$commentModel = new Comment($currentPost['post_id'], 0);
$comments = $commentModel->getByPost();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết bài viết - TechConnect</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/variables.css">
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/posts.css">
</head>
<body>
    <!-- Navbar -->
    <?php include __DIR__ . '/../../components/layout/navbar.php'; ?>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Back button -->
                <a href="/home" class="btn btn-link mb-3">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>

                <!-- Post Detail Card -->
                <div class="card post-detail-card shadow-sm">
                    <!-- Post Header -->
                    <div class="card-body border-bottom">
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
                    <div class="card-body">
                        <p class="fs-5 mb-3"><?= nl2br(htmlspecialchars($currentPost['content'])) ?></p>
                    </div>

                    <!-- Post Images (Carousel) -->
                    <?php 
                    $images = $currentPost['images'] ?? [];
                    $imageCount = count($images);
                    $hasMultipleImages = $imageCount > 1;
                    ?>
                    
                    <?php if ($imageCount > 0): ?>
                    <div class="post-carousel-container" data-post-id="<?= $currentPost['post_id'] ?>">
                        <?php if ($hasMultipleImages): ?>
                        <button class="carousel-nav carousel-nav-prev" onclick="postCarousel.prev(<?= $currentPost['post_id'] ?>); event.stopPropagation();">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="carousel-nav carousel-nav-next" onclick="postCarousel.next(<?= $currentPost['post_id'] ?>); event.stopPropagation();">
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
                                  onclick="postCarousel.goTo(<?= $currentPost['post_id'] ?>, <?= $i ?>); event.stopPropagation();"></span>
                            <?php endfor; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Post Stats -->
                    <div class="card-body border-top">
                        <div class="d-flex justify-content-between mb-3">
                            <span><i class="fas fa-heart text-danger"></i> <?= $currentPost['like_count'] ?> lượt thích</span>
                            <span><?= count($comments) ?> bình luận</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-around border-top pt-2">
                            <button class="btn btn-link text-decoration-none like-btn" data-post-id="<?= $currentPost['post_id'] ?>">
                                <i class="far fa-heart"></i> Thích
                            </button>
                            <button class="btn btn-link text-decoration-none">
                                <i class="far fa-comment"></i> Bình luận
                            </button>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="card-body border-top">
                        <h5 class="mb-4">Bình luận (<?= count($comments) ?>)</h5>

                        <!-- Comment Form -->
                        <div class="mb-4">
                            <form class="comment-form" data-post-id="<?= $currentPost['post_id'] ?>">
                                <div class="d-flex gap-2">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px; flex-shrink: 0;">
                                        <span class="text-white fw-bold">
                                            <?= strtoupper(substr($_SESSION['username'] ?? $_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <textarea 
                                            class="form-control comment-input" 
                                            rows="2" 
                                            placeholder="Viết bình luận..."
                                            name="comment_content"
                                        ></textarea>
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">
                                            <i class="fas fa-paper-plane"></i> Gửi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Comments List -->
                        <div class="comments-list">
                            <?php if (empty($comments)): ?>
                                <p class="text-muted text-center py-4">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
                            <?php else: ?>
                                <?php foreach ($comments as $comment): ?>
                                <div class="comment-item mb-3 p-3 bg-light rounded">
                                    <div class="d-flex gap-2">
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 36px; height: 36px; flex-shrink: 0;">
                                            <span class="text-white fw-bold small">
                                                <?= strtoupper(substr($comment['Username'] ?? 'U', 0, 1)) ?>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="mb-1"><?= htmlspecialchars($comment['Username'] ?? 'Anonymous') ?></h6>
                                                <small class="text-muted">
                                                    <?php
                                                    $commentTime = $comment['CommentTime'] ?? $comment['CreatedAt'] ?? null;
                                                    if ($commentTime) {
                                                        $timestamp = strtotime($commentTime);
                                                        $diff = time() - $timestamp;
                                                        if ($diff < 60) echo 'Vừa xong';
                                                        elseif ($diff < 3600) echo floor($diff / 60) . ' phút trước';
                                                        elseif ($diff < 86400) echo floor($diff / 3600) . ' giờ trước';
                                                        else echo date('d/m/Y H:i', $timestamp);
                                                    } else {
                                                        echo 'Vừa xong';
                                                    }
                                                    ?>
                                                </small>
                                            </div>
                                            <p class="mb-0"><?= nl2br(htmlspecialchars($comment['Content'] ?? '')) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/carousel.js?v=1"></script>
    <script src="/assets/js/posts.js?v=20251021v4"></script>
</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>

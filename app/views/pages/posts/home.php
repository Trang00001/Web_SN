<?php
/**
 * Trang chủ - Newsfeed
 */

require_once __DIR__ . '/../../controllers/PostController.php';
require_once __DIR__ . '/../../models/Database.php';

session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: /auth/login.php');
    exit;
}

$postController = new PostController();
$posts = $postController->getAllPosts();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Social Network</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/posts.css" rel="stylesheet">
    <link href="/assets/css/global.css" rel="stylesheet">
</head>
<body>
    <!-- Include Navbar -->
    <?php include __DIR__ . '/../components/layout/navbar.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <!-- Create Post Card -->
                <div class="create-post-card mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="post-avatar me-3">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="text-white fw-bold"><?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)) ?></span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <button class="btn btn-light w-100 text-start" onclick="alert('Vui lòng sử dụng trang chính để tạo bài viết')">
                                        Bạn đang nghĩ gì?
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-around">
                                <button class="btn btn-link text-decoration-none" onclick="alert('Chức năng đang phát triển')">
                                    <i class="fas fa-image text-success me-1"></i>
                                    Ảnh/Video
                                </button>
                                <button class="btn btn-link text-decoration-none" onclick="alert('Chức năng đang phát triển')">
                                    <i class="fas fa-smile text-warning me-1"></i>
                                    Cảm xúc
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Posts Feed -->
                <div class="posts-feed">
                    <?php if (empty($posts)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có bài viết nào</h5>
                            <p class="text-muted">Hãy tạo bài viết đầu tiên của bạn!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                            <?php include __DIR__ . '/../components/posts/post-card.php'; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Toast -->
    <?php include __DIR__ . '/../components/layout/toast.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/posts.js"></script>
</body>
</html>

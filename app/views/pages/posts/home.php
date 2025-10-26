<?php
/**
 * Trang chủ - PROTOTYPE VERSION
 * Đơn giản để dễ điều chỉnh
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

// User demo đơn giản
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'Demo User';
}

// Lấy bài viết mới từ session (nếu có)
$newPosts = $_SESSION['new_posts'] ?? [];

// Mock data cơ bản
$defaultPosts = [
    [
        'post_id' => 1,
        'username' => 'Alice Johnson',
        'content' => 'Chào mọi người! Hôm nay thật là một ngày tuyệt vời 🌟',
        'media_url' => 'https://picsum.photos/500/400?random=1',
        'like_count' => 15,
        'comment_count' => 3,
        'created_at' => '2 giờ trước'
    ],
    [
        'post_id' => 2,
        'username' => 'Bob Smith',
        'content' => 'Vừa hoàn thành dự án mới! 💪',
        'media_url' => null,
        'like_count' => 28,
        'comment_count' => 7,
        'created_at' => '4 giờ trước'
    ]
];

// Kết hợp bài viết mới với mock data
$posts = array_merge($newPosts, $defaultPosts);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechConnect - Prototype</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../../../public/assets/css/theme.css" rel="stylesheet">
    <link href="../../../../public/assets/css/posts.css" rel="stylesheet">
</head>
<body>

    <!-- Main Content -->
    <div class="main-container">
        <div class="content-grid">
            <!-- Left Sidebar -->
            <aside class="left-sidebar">
                <?php include __DIR__ . '/../../components/layout/sidebar-left.php'; ?>
            </aside>

            <!-- Center Feed -->
            <main class="center-feed">
                <!-- Create Post -->
                <?php include __DIR__ . '/create-post.php'; ?>

                <!-- Posts Feed -->
                <section class="posts-feed">
                    <?php foreach ($posts as $post): ?>
                        <?php include __DIR__ . '/../../components/posts/post-card.php'; ?>
                    <?php endforeach; ?>
                </section>
            </main>

            <!-- Right Sidebar -->
            <aside class="right-sidebar">
                <?php include __DIR__ . '/../../components/layout/sidebar-right.php'; ?>
            </aside>
        </div>
    </div>

    <!-- Create Post Modal -->
    <div class="modal fade" id="createPostModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo bài viết</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 40px; height: 40px;">
                            <span class="text-white fw-bold"><?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)) ?></span>
                        </div>
                        <div>
                            <h6 class="mb-0"><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></h6>
                            <small class="text-muted">Công khai</small>
                        </div>
                    </div>
                    <textarea class="form-control border-0 fs-5" rows="4" 
                              placeholder="Bạn đang nghĩ gì?" 
                              style="resize: none; box-shadow: none;"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" id="post-submit-btn">Đăng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal (Shared) -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img id="modalImage" src="" class="img-fluid" alt="Enlarged image">
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../../public/assets/js/posts.js"></script>
</body>
</html>

<?php
// Lấy nội dung buffer
$content = ob_get_clean();

// Áp dụng layout
require_once __DIR__ . '/../../layouts/main.php';

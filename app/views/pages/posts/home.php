<?php
/**
 * Trang chủ - MVC Pattern
 * Sử dụng PostController để xử lý logic
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

// AUTO-LOGIN FOR TESTING - Remove in production
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;      // Alice
    $_SESSION['username'] = 'Alice';
    $_SESSION['email'] = 'alice@test.com';
}

// Load Controller thay vì Model
require_once __DIR__ . '/../../../controllers/PostController.php';

// Lấy posts qua Controller - tất cả logic đã được xử lý ở đây
$postController = new PostController();
$posts = $postController->getAllPosts();

// Load all images for each post
require_once __DIR__ . '/../../../models/Image.php';
foreach ($posts as &$post) {
    $imageModel = new Image($post['post_id'], '');
    $post['images'] = $imageModel->getByPostId($post['post_id']);
}
unset($post); // Break reference
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
    <link href="/public/assets/css/theme.css" rel="stylesheet">
    <link href="/public/assets/css/posts.css" rel="stylesheet">
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
                    <form id="create-post-form">
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
                                  id="post-content-textarea"
                                  name="content"
                                  style="resize: none; box-shadow: none;"></textarea>
                        
                        <!-- Image Preview Area -->
                        <div id="image-preview-container" class="mt-3" style="display: none;">
                            <div class="d-flex flex-wrap gap-2" id="image-preview-list"></div>
                        </div>
                        
                        <!-- Add Photo Button -->
                        <label for="post-image-input" class="d-flex align-items-center gap-2 mt-3 p-2 border rounded" style="cursor: pointer; margin-bottom: 0;">
                            <i class="fas fa-image text-success fs-5"></i>
                            <span>Ảnh/Video</span>
                        </label>
                        <input type="file" id="post-image-input" class="d-none" multiple accept="image/*">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="create-post-form" class="btn btn-primary w-100" id="post-submit-btn">Đăng</button>
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

    <!-- Post Detail Modal -->
    <?php include __DIR__ . '/../../components/modal/post-detail-modal.php'; ?>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/assets/js/carousel.js?v=1"></script>
    <script src="/public/assets/js/posts.js?v=20251021v4"></script>
    
    <script>
    /**
     * Open post detail modal
     */
    function openPostDetail(postId) {
        const modal = new bootstrap.Modal(document.getElementById('postDetailModal'));
        const modalContent = document.getElementById('postDetailContent');
        
        // Show loading
        modalContent.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Đang tải...</p>
            </div>
        `;
        
        // Show modal
        modal.show();
        
        // Load post detail
        fetch(`/api/posts/get_detail.php?id=${postId}`)
            .then(response => response.text())
            .then(html => {
                modalContent.innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading post detail:', error);
                modalContent.innerHTML = `
                    <div class="alert alert-danger m-4">
                        <i class="fas fa-exclamation-circle"></i>
                        Không thể tải bài viết. Vui lòng thử lại!
                    </div>
                `;
            });
    }
    </script>
</body>
</html>

<?php
// Lấy nội dung buffer
$content = ob_get_clean();

// Áp dụng layout
require_once __DIR__ . '/../../layouts/main.php';

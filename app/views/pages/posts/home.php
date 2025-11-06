<?php
/**
 * Trang chủ - MVC Pattern
 * Sử dụng PostController để xử lý logic
 */

// Load helpers for authentication
require_once __DIR__ . '/../../../../core/Helpers.php';

// Require authentication
require_auth();

// session_start() already called in public/index.php

// Load Controller thay vì Model
require_once __DIR__ . '/../../../controllers/PostController.php';

// Lấy userId từ session
$userId = $_SESSION['user_id'] ?? null;

// Kiểm tra filter "Đã lưu"
$showSaved = isset($_GET['saved']) && $_GET['saved'] == 1;

// Lấy search keyword
$searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : null;

// Lấy category filter từ URL (nếu có)
$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;

// Lấy posts
require_once __DIR__ . '/../../../../core/Database.php';
$db = new Database();

if ($showSaved) {
    // Lấy bài viết đã lưu
    $posts = $db->select(
        "SELECT p.PostID as post_id, p.Content as content, p.PostTime as created_at,
                a.Username as username, a.AccountID as author_id,
                (SELECT COUNT(*) FROM PostLike WHERE PostID = p.PostID) as like_count,
                (SELECT COUNT(*) FROM Comment WHERE PostID = p.PostID) as comment_count,
                EXISTS(SELECT 1 FROM PostLike WHERE PostID = p.PostID AND AccountID = ?) as user_liked,
                pc.CategoryID as category_id, pc.CategoryName as category_name
         FROM SavedPost sp
         JOIN Post p ON sp.PostID = p.PostID
         JOIN Account a ON p.AuthorID = a.AccountID
         LEFT JOIN PostCategory pc ON p.CategoryID = pc.CategoryID
         WHERE sp.AccountID = ?
         ORDER BY sp.SavedTime DESC",
        [$userId, $userId]
    );
} elseif ($searchKeyword) {
    // Tìm kiếm theo từ khóa
    $searchPattern = "%{$searchKeyword}%";
    $posts = $db->select(
        "SELECT p.PostID as post_id, p.Content as content, p.PostTime as created_at,
                a.Username as username, a.AccountID as author_id,
                (SELECT COUNT(*) FROM PostLike WHERE PostID = p.PostID) as like_count,
                (SELECT COUNT(*) FROM Comment WHERE PostID = p.PostID) as comment_count,
                EXISTS(SELECT 1 FROM PostLike WHERE PostID = p.PostID AND AccountID = ?) as user_liked,
                pc.CategoryID as category_id, pc.CategoryName as category_name
         FROM Post p
         JOIN Account a ON p.AuthorID = a.AccountID
         LEFT JOIN PostCategory pc ON p.CategoryID = pc.CategoryID
         WHERE p.Content LIKE ? OR a.Username LIKE ?
         ORDER BY p.PostTime DESC",
        [$userId, $searchPattern, $searchPattern]
    );
} else {
    // Lấy posts thông thường
    $postController = new PostController();
    $posts = $postController->getAllPosts($userId, $categoryId);
}

// Load all images for each post
require_once __DIR__ . '/../../../models/Image.php';
foreach ($posts as &$post) {
    $imageModel = new Image($post['post_id'], '');
    $post['images'] = $imageModel->getByPostId($post['post_id']);
}
unset($post); // Break reference

// Get current user info for create post section
$currentUser = 'User';
$userInitial = 'U';
$userAvatar = '';

try {
    require_once __DIR__ . '/../../../../core/Database.php';
    $db = new Database();
    $userResult = $db->select(
        "SELECT a.Username, p.FullName, p.AvatarURL 
         FROM Account a 
         LEFT JOIN Profile p ON a.AccountID = p.AccountID 
         WHERE a.AccountID = ?", 
        [$userId]
    );
    
    if ($userResult && isset($userResult[0])) {
        $username = $userResult[0]['Username'] ?? 'User';
        $fullName = $userResult[0]['FullName'] ?? '';
        $userAvatar = $userResult[0]['AvatarURL'] ?? '';
        
        // Priority: FullName > Username
        $currentUser = !empty($fullName) ? $fullName : $username;
        $userInitial = strtoupper(substr($currentUser, 0, 1));
    }
} catch (Exception $e) {
    error_log("Home page user info error: " . $e->getMessage());
}
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
    <link href="/assets/css/variables.css" rel="stylesheet">
    <link href="/assets/css/global.css" rel="stylesheet">
    <link href="/assets/css/layout.css" rel="stylesheet">
    <link href="/assets/css/theme.css" rel="stylesheet">
    <link href="/assets/css/posts.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php include __DIR__ . '/../../components/layout/navbar.php'; ?>

    <!-- Main Content -->
    <div class="main-container">
        <div class="content-grid">
            <!-- Left Sidebar -->
            <aside class="left-sidebar">
                <?php include __DIR__ . '/../../components/layout/sidebar-left.php'; ?>
            </aside>

            <!-- Center Feed -->
            <main class="center-feed">
                <!-- Search Result Header -->
                <?php if ($searchKeyword): ?>
                <div class="tech-card mb-3">
                    <h5 class="mb-1">
                        <i class="fas fa-search me-2"></i>
                        Kết quả tìm kiếm: "<?= htmlspecialchars($searchKeyword) ?>"
                    </h5>
                    <p class="text-muted mb-0"><?= count($posts) ?> bài viết</p>
                    <a href="/home" class="btn btn-sm btn-outline-secondary mt-2">
                        <i class="fas fa-times me-1"></i>Xóa bộ lọc
                    </a>
                </div>
                <?php endif; ?>
                
                <!-- Create Post -->
                <?php if (!$searchKeyword): ?>
                    <?php include __DIR__ . '/create-post.php'; ?>
                <?php endif; ?>

                <!-- Posts Feed -->
                <section class="posts-feed">
                    <?php if (count($posts) > 0): ?>
                        <?php foreach ($posts as $post): ?>
                            <?php include __DIR__ . '/../../components/posts/post-card.php'; ?>
                        <?php endforeach; ?>
                    <?php elseif ($searchKeyword): ?>
                        <!-- Chỉ hiện "không tìm thấy" khi đang search -->
                        <div class="tech-card text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5>Không tìm thấy kết quả</h5>
                            <p class="text-muted">Thử tìm kiếm với từ khóa khác</p>
                        </div>
                    <?php endif; ?>
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
                                 style="width: 40px; height: 40px; overflow: hidden;">
                                <?php if (!empty($userAvatar)): ?>
                                    <img src="<?= htmlspecialchars($userAvatar) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <span class="text-white fw-bold"><?= $userInitial ?></span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h6 class="mb-0"><?= htmlspecialchars($currentUser) ?></h6>
                                <small class="text-muted">Công khai</small>
                            </div>
                        </div>
                        <textarea class="form-control border-0 fs-5" rows="4" 
                                  placeholder="Bạn đang nghĩ gì?" 
                                  id="post-content-textarea"
                                  name="content"
                                  style="resize: none; box-shadow: none;"></textarea>
                        
                        <!-- Category Selection -->
                        <div class="mt-3">
                            <select class="form-select form-select-sm" name="category_id" id="post-category-select">
                                <?php
                                try {
                                    require_once __DIR__ . '/../../../models/PostCategory.php';
                                    $categoryModel = new PostCategory();
                                    $categories = $categoryModel->getAll();
                                    if ($categories && is_array($categories)) {
                                        foreach ($categories as $cat) {
                                            echo '<option value="' . $cat['CategoryID'] . '">' . htmlspecialchars($cat['CategoryName']) . '</option>';
                                        }
                                    }
                                } catch (Exception $e) {
                                    echo '<option value="1">Chung</option>';
                                    error_log("Category load error: " . $e->getMessage());
                                }
                                ?>
                            </select>
                        </div>
                        
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
    <script src="/assets/js/carousel.js?v=1"></script>
    <script src="/assets/js/posts.js?v=20251021v4"></script>
    
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

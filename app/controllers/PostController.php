<?php
class PostController {
    public function index() {
        $title = "Bảng tin";
        require __DIR__ . '/../views/pages/posts/home.php';
    }
    
    public function create() {
        $title = "Tạo bài viết mới";
        require __DIR__ . '/../views/pages/posts/create_post.php';
    }
    
    public function show($id) {
        $title = "Chi tiết bài viết";
        require __DIR__ . '/../views/pages/posts/detail.php';
    }
    
    public function store() {
        // Handle post creation
        header('Content-Type: application/json');
        
        $content = $_POST['content'] ?? '';
        if (empty($content)) {
            echo json_encode(['success' => false, 'error' => 'Nội dung không được để trống']);
            return;
        }
        
        // TODO: Save to database
        $_SESSION['new_posts'][] = [
            'post_id' => time(),
            'username' => $_SESSION['username'] ?? 'Anonymous',
            'content' => $content,
            'media_url' => null,
            'like_count' => 0,
            'comment_count' => 0,
            'created_at' => 'Vừa xong'
        ];
        
        echo json_encode(['success' => true]);
    }
}

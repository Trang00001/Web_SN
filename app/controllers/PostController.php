<?php
/**
 * PostController - MVC Pattern
 * Xử lý tất cả logic liên quan đến bài viết
 */

require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Image.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/PostLike.php';
require_once __DIR__ . '/../models/Notification.php';

class PostController {
    
    /**
     * Kiểm tra user đã like post chưa
     * @param int $userId
     * @param int $postId
     * @return bool
     */
    private function checkUserLiked($userId, $postId) {
        if (!$userId || !$postId) {
            return false;
        }
        
        try {
            $postLike = new PostLike($userId, $postId);
            return $postLike->isLiked();
        } catch (Exception $e) {
            error_log("PostController::checkUserLiked() - Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy tất cả bài viết để hiển thị trên trang chủ
     * @param int $userId (optional) - ID của user đang xem để check liked status
     * @param int $categoryId (optional) - Filter theo category
     * @return array Mảng các bài viết đã được format
     */
    public function getAllPosts($userId = null, $categoryId = null) {
        try {
            $postModel = new Post(0);
            $postsFromDB = $postModel->getAll();
            
            $posts = [];
            if ($postsFromDB && is_array($postsFromDB)) {
                foreach ($postsFromDB as $row) {
                    // Filter by category if specified
                    if ($categoryId !== null && isset($row['CategoryID'])) {
                        if ($row['CategoryID'] != $categoryId) {
                            continue; // Skip posts that don't match category
                        }
                    }
                    $posts[] = $this->formatPostData($row, $userId);
                }
            }
            
            // Fallback nếu không có posts
            if (empty($posts)) {
                $posts = [[
                    'post_id' => 0,
                    'username' => 'System',
                    'content' => 'Chưa có bài viết nào. Hãy tạo bài viết đầu tiên! 🎉',
                    'media_url' => null,
                    'like_count' => 0,
                    'comment_count' => 0,
                    'created_at' => 'Vừa xong'
                ]];
            }
            
            return $posts;
            
        } catch (Exception $e) {
            error_log("PostController::getAllPosts() - Error: " . $e->getMessage());
            return [[
                'post_id' => 0,
                'username' => 'System',
                'content' => 'Lỗi khi tải bài viết. Vui lòng thử lại sau.',
                'media_url' => null,
                'like_count' => 0,
                'comment_count' => 0,
                'created_at' => 'Vừa xong'
            ]];
        }
    }
    
    /**
     * Lấy bài viết theo ID
     * @param int $postId
     * @param int $userId (optional) - ID của user đang xem để check liked status
     * @return array|null
     */
    public function getPostById($postId, $userId = null) {
        try {
            $postModel = new Post(0);
            $postModel->setPostID($postId);
            $result = $postModel->getById();
            
            if ($result) {
                return $this->formatPostData($result, $userId);
            }
            
            return null;
            
        } catch (Exception $e) {
            error_log("PostController::getPostById() - Error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Tạo bài viết mới
     * @param int $authorId
     * @param string $content
     * @param array $imageUrls (optional)
     * @return array Response với success status và post_id
     */
    public function createPost($authorId, $content, $imageUrls = []) {
        try {
            // Validate input
            if (empty($content)) {
                return [
                    'success' => false,
                    'error' => 'Nội dung không được để trống'
                ];
            }
            
            if (strlen($content) > 5000) {
                return [
                    'success' => false,
                    'error' => 'Nội dung quá dài (tối đa 5000 ký tự)'
                ];
            }
            
            // Create post
            $postModel = new Post($authorId, $content);
            $postId = $postModel->create();
            
            if (!$postId) {
                return [
                    'success' => false,
                    'error' => 'Không thể tạo bài viết'
                ];
            }
            
            // Add images if provided
            $imageCount = 0;
            if (!empty($imageUrls) && is_array($imageUrls)) {
                foreach ($imageUrls as $imageUrl) {
                    $imageModel = new Image($postId, $imageUrl);
                    if ($imageModel->add()) {
                        $imageCount++;
                    }
                }
            }
            
            return [
                'success' => true,
                'post_id' => $postId,
                'image_count' => $imageCount,
                'message' => 'Đã tạo bài viết thành công'
            ];
            
        } catch (Exception $e) {
            error_log("PostController::createPost() - Error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Xóa bài viết
     * @param int $postId
     * @param int $userId (để verify ownership)
     * @return array Response
     */
    public function deletePost($postId, $userId) {
        try {
            // Xóa các bản ghi liên quan trước (saved posts, likes, comments, images)
            $db = new Database();
            $conn = $db->getConnection();
            
            // Xóa saved posts
            $stmt = $conn->prepare("DELETE FROM SavedPost WHERE PostID = ?");
            $stmt->bind_param('i', $postId);
            $stmt->execute();
            $stmt->close();
            
            // Xóa likes
            $stmt = $conn->prepare("DELETE FROM PostLike WHERE PostID = ?");
            $stmt->bind_param('i', $postId);
            $stmt->execute();
            $stmt->close();
            
            // Xóa comments
            $stmt = $conn->prepare("DELETE FROM Comment WHERE PostID = ?");
            $stmt->bind_param('i', $postId);
            $stmt->execute();
            $stmt->close();
            
            // Xóa images
            $stmt = $conn->prepare("DELETE FROM Image WHERE PostID = ?");
            $stmt->bind_param('i', $postId);
            $stmt->execute();
            $stmt->close();
            
            // Cuối cùng xóa post
            $postModel = new Post(0);
            $postModel->setPostID($postId);
            $result = $postModel->delete();
            
            return [
                'success' => $result,
                'message' => $result ? 'Đã xóa bài viết' : 'Không thể xóa bài viết'
            ];
            
        } catch (Exception $e) {
            error_log("PostController::deletePost() - Error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Không thể xóa bài viết: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Cập nhật nội dung bài viết
     * @param int $postId
     * @param int $userId (để verify ownership)
     * @param string $content
     * @return array
     */
    public function updatePost($postId, $userId, $content) {
        try {
            // Validate
            if (empty($content)) {
                return [
                    'success' => false,
                    'error' => 'Nội dung không được để trống'
                ];
            }
            
            // Update post
            $postModel = new Post(0);
            $postModel->setPostID($postId);
            $postModel->setContent($content);
            $result = $postModel->update();
            
            return [
                'success' => $result,
                'message' => $result ? 'Đã cập nhật bài viết' : 'Không thể cập nhật bài viết'
            ];
            
        } catch (Exception $e) {
            error_log("PostController::updatePost() - Error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Lấy comments của một bài viết
     * @param int $postId
     * @return array
     */
    public function getComments($postId) {
        try {
            $commentModel = new Comment($postId, 0);
            $commentsFromDB = $commentModel->getByPost();
            
            $comments = [];
            if ($commentsFromDB && is_array($commentsFromDB)) {
                foreach ($commentsFromDB as $row) {
                    $comments[] = [
                        'comment_id' => $row['CommentID'] ?? 0,
                        'username' => $row['Username'] ?? 'Anonymous',
                        'content' => $row['Content'] ?? '',
                        'created_at' => $this->formatTimeAgo($row['CommentTime'] ?? $row['CreatedAt'] ?? null)
                    ];
                }
            }
            
            return $comments;
            
        } catch (Exception $e) {
            error_log("PostController::getComments() - Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Thêm comment vào bài viết
     * @param int $postId
     * @param int $userId
     * @param string $content
     * @return array Response
     */
    public function addComment($postId, $userId, $content) {
        try {
            // Validate
            if (empty($content)) {
                return [
                    'success' => false,
                    'error' => 'Nội dung comment không được để trống'
                ];
            }
            
            if (strlen($content) > 1000) {
                return [
                    'success' => false,
                    'error' => 'Comment quá dài (tối đa 1000 ký tự)'
                ];
            }
            
            // Add comment
            $commentModel = new Comment($postId, $userId, $content);
            $result = $commentModel->add();
            
            if ($result) {
                // Tạo notification cho tác giả bài viết (nếu không phải tự comment)
                try {
                    $postModel = new Post(0);
                    $postModel->setPostID($postId);
                    $postData = $postModel->getById();
                    
                    // Kiểm tra xem có dữ liệu không
                    if (!empty($postData) && is_array($postData)) {
                        $post = $postData[0] ?? null;
                        
                        if ($post) {
                            // Lấy AuthorID từ dữ liệu trả về
                            $authorId = $post['AuthorID'] ?? null;
                            
                            error_log("DEBUG addComment - authorId: $authorId, userId: $userId");
                            
                            if ($authorId && $authorId != $userId) { // tránh tự thông báo cho chính mình
                                require_once __DIR__ . '/../../core/Helpers.php';
                                $commenterName = getUsername($userId);
                                
                                // Rút ngắn nội dung comment nếu quá dài
                                $commentPreview = mb_strlen($content) > 50 
                                    ? mb_substr($content, 0, 50) . '...' 
                                    : $content;
                                
                                $notificationContent = "$commenterName đã bình luận: \"$commentPreview\"";
                                
                                $notification = new Notification($authorId, 'comment', $notificationContent);
                                $createResult = $notification->create();
                                
                                if ($createResult) {
                                    error_log("DEBUG addComment - Notification tạo thành công cho authorId: $authorId");
                                } else {
                                    error_log("DEBUG addComment - Notification không lưu được (create() trả về false)");
                                }
                            } else {
                                error_log("DEBUG addComment - Không tạo notification (authorId null hoặc tự comment)");
                            }
                        } else {
                            error_log("DEBUG addComment - Post data rỗng sau khi lấy từ getById()");
                        }
                    } else {
                        error_log("DEBUG addComment - Không tìm thấy post với ID: $postId");
                    }
                } catch (Exception $e) {
                    error_log("DEBUG addComment - Exception khi tạo notification: " . $e->getMessage());
                    // Không throw exception, chỉ log vì comment đã được thêm thành công
                }
                
                return [
                    'success' => true,
                    'comment' => [
                        'post_id' => $postId,
                        'content' => $content,
                        'created_at' => 'Vừa xong'
                    ],
                    'message' => 'Đã thêm bình luận'
                ];
            }
            
            return [
                'success' => false,
                'error' => 'Không thể thêm bình luận'
            ];
            
        } catch (Exception $e) {
            error_log("PostController::addComment() - Error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Toggle like/unlike bài viết
     * @param int $postId
     * @param int $userId
     * @param string $action 'like' hoặc 'unlike'
     * @return array Response
     */
    
    public function toggleLike($postId, $userId, $action) {
    try {
        require_once __DIR__ . '/../models/Post.php';
        require_once __DIR__ . '/../models/PostLike.php';
        require_once __DIR__ . '/../models/Notification.php';

        // 1️⃣ Thao tác like/unlike
        $postLikeModel = new PostLike($userId, $postId);
        $result = false;
        $message = '';

        if ($action === 'like') {
            $result = $postLikeModel->like();
            $message = 'Đã thích bài viết';
        } else {
            $result = $postLikeModel->unlike();
            $message = 'Đã bỏ thích';
        }

        // 2️⃣ Lấy số like mới
        $likeCount = $postLikeModel->getCountByPost();

        // 3️⃣ Nếu là like và thao tác thành công, tạo notification
        if ($action === 'like' && $result) {
            try {
                $postModel = new Post(0);
                $postModel->setPostID($postId);
                $postData = $postModel->getById();
                
                // Kiểm tra xem có dữ liệu không
                if (empty($postData) || !is_array($postData)) {
                    error_log("DEBUG toggleLike - Không tìm thấy post với ID: $postId");
                } else {
                    $post = $postData[0] ?? null; // Lấy dòng đầu tiên
                    
                    if ($post) {
                        // Lấy AuthorID từ dữ liệu trả về
                        $authorId = $post['AuthorID'] ?? null;
                        
                        error_log("DEBUG toggleLike - authorId: $authorId, userId: $userId");
                        
                        if ($authorId && $authorId != $userId) { // tránh tự thông báo cho chính mình
                            require_once __DIR__ . '/../../core/Helpers.php';
                            $likerName = getUsername($userId);
                            $content = "$likerName đã thích bài viết của bạn";
                            
                            $notification = new Notification($authorId, 'like', $content);
                            $createResult = $notification->create();
                            
                            if ($createResult) {
                                error_log("DEBUG toggleLike - Notification tạo thành công cho authorId: $authorId");
                            } else {
                                error_log("DEBUG toggleLike - Notification không lưu được (create() trả về false)");
                            }
                        } else {
                            error_log("DEBUG toggleLike - Không tạo notification (authorId null hoặc tự like)");
                        }
                    } else {
                        error_log("DEBUG toggleLike - Post data rỗng sau khi lấy từ getById()");
                    }
                }
            } catch (Exception $e) {
                error_log("DEBUG toggleLike - Exception khi tạo notification: " . $e->getMessage());
            }
        }

        return [
            'success' => $result,
            'action' => $action,
            'new_count' => $likeCount,
            'message' => $message
        ];

    } catch (Exception $e) {
        error_log("PostController::toggleLike() - Error: " . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}



    
    /**
     * Upload ảnh cho bài viết
     * @param array $file $_FILES['image']
     * @return array Response với image_url
     */
    public function uploadImage($file) {
        try {
            // Validate file
            if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
                return [
                    'success' => false,
                    'error' => 'Không có file hoặc file lỗi'
                ];
            }
            
            // Check file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file['type'], $allowedTypes)) {
                return [
                    'success' => false,
                    'error' => 'File không phải ảnh (chỉ chấp nhận JPG, PNG, GIF, WEBP)'
                ];
            }
            
            // Check file size (max 5MB)
            if ($file['size'] > 5 * 1024 * 1024) {
                return [
                    'success' => false,
                    'error' => 'File quá lớn (tối đa 5MB)'
                ];
            }
            
            // Create upload directory if not exists
            $uploadDir = __DIR__ . '/../../public/uploads/posts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $uploadPath = $uploadDir . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // Return absolute URL
                $imageUrl = 'http://localhost/WEB_SN/public/uploads/posts/' . $filename;
                
                return [
                    'success' => true,
                    'image_url' => $imageUrl,
                    'filename' => $filename
                ];
            }
            
            return [
                'success' => false,
                'error' => 'Không thể lưu file'
            ];
            
        } catch (Exception $e) {
            error_log("PostController::uploadImage() - Error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Format dữ liệu post từ database
     * @param array $row
     * @param int $userId (optional) - ID của user đang xem để check liked status
     * @return array
     */
    private function formatPostData($row, $userId = null) {
        // Fix image URL
        $imageUrl = $row['ImageUrl'] ?? null;
        if ($imageUrl) {
            if (!str_starts_with($imageUrl, 'http')) {
                if (str_starts_with($imageUrl, '/')) {
                    $imageUrl = 'http://localhost' . $imageUrl;
                } else {
                    $imageUrl = 'http://localhost/WEB_SN/public/' . ltrim($imageUrl, '/');
                }
            }
        }
        
        // Format time ago
        $createdAt = $this->formatTimeAgo($row['CreatedAt'] ?? $row['PostTime'] ?? null);
        
        // Check if user liked this post
        $userLiked = false;
        if ($userId) {
            $userLiked = $this->checkUserLiked($userId, $row['PostID']);
        }
        
        return [
            'post_id' => $row['PostID'],
            'username' => $row['Username'] ?? 'Unknown User',
            'avatar_url' => $row['AvatarURL'] ?? null,
            'content' => $row['Content'] ?? '',
            'media_url' => $imageUrl,
            'like_count' => $row['LikeCount'] ?? 0,
            'comment_count' => $row['CommentCount'] ?? 0,
            'created_at' => $createdAt,
            'user_liked' => $userLiked,
            'category_id' => $row['CategoryID'] ?? 1,
            'category_name' => $row['CategoryName'] ?? 'Cuộc sống'
        ];
    }
    
    /**
     * Format timestamp thành "X phút trước", "X giờ trước", etc.
     * @param string|null $timestamp
     * @return string
     */
    private function formatTimeAgo($timestamp) {
        if (!$timestamp) {
            return 'Vừa xong';
        }
        
        // Set timezone
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        
        $createdAt = strtotime($timestamp);
        $now = time();
        $diff = $now - $createdAt;
        
        // Handle negative diff or very recent
        if ($diff < 0 || $diff < 60) {
            return 'Vừa xong';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' phút trước';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' giờ trước';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' ngày trước';
        } else {
            // Display date if > 7 days
            return date('d/m/Y', $createdAt);
        }
    }
}
?>

<?php
/**
 * PostController - MVC Pattern
 * Xử lý tất cả logic liên quan đến bài viết
 */

require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Image.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/PostLike.php';

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
     * @return array Mảng các bài viết đã được format
     */
    public function getAllPosts($userId = null) {
        try {
            $postModel = new Post(0);
            $postsFromDB = $postModel->getAll();
            
            $posts = [];
            if ($postsFromDB && is_array($postsFromDB)) {
                foreach ($postsFromDB as $row) {
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
            // TODO: Verify user owns the post
            // For now, just delete
            
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
            $postLikeModel = new PostLike($userId, $postId);
            
            if ($action === 'like') {
                $result = $postLikeModel->add();
                $message = 'Đã thích bài viết';
            } else {
                $result = $postLikeModel->remove();
                $message = 'Đã bỏ thích';
            }
            
            if ($result) {
                // Get new like count
                $likeCount = $postLikeModel->getCountByPost();
                
                return [
                    'success' => true,
                    'action' => $action,
                    'new_count' => $likeCount,
                    'message' => $message
                ];
            }
            
            return [
                'success' => false,
                'error' => 'Không thể thực hiện'
            ];
            
        } catch (Exception $e) {
            error_log("PostController::toggleLike() - Error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
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
                $imageUrl = 'http://localhost/WEB-SN/public/uploads/posts/' . $filename;
                
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
                    $imageUrl = 'http://localhost/WEB-SN/public/' . ltrim($imageUrl, '/');
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
            'content' => $row['Content'] ?? '',
            'media_url' => $imageUrl,
            'like_count' => $row['LikeCount'] ?? 0,
            'comment_count' => $row['CommentCount'] ?? 0,
            'created_at' => $createdAt,
            'user_liked' => $userLiked
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

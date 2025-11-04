<?php
/**
 * ProfileController - MVC Pattern
 * Xử lý logic liên quan đến profile
 */

require_once __DIR__ . '/../models/Profile.php';
require_once __DIR__ . '/../models/Account.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../../core/Helpers.php';

class ProfileController {
    
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Hiển thị trang profile
     */
    public function index() {
        ensure_session_started();
        require_auth();
        
        $accountID = $_SESSION['user_id'] ?? null;
        
        if (!$accountID) {
            header('Location: /login');
            exit();
        }
        
        // Lấy thông tin profile
        $profileData = $this->getProfile($accountID);
        
        if (!$profileData) {
            echo "Không tìm thấy thông tin profile cho AccountID: " . $accountID;
            exit();
        }
        
        // Lấy bài viết của user
        $posts = $this->getUserPosts($accountID);
        $postCount = count($posts);
        
        // Lấy ảnh của user
        $photos = $this->getUserPhotos($accountID);
        
        // Truyền dữ liệu vào view
        $name = $profileData['full_name'];
        $email = $profileData['email'];
        $username = $profileData['username'];
        $avatar = $profileData['avatar'];
        $gender = $profileData['gender'];
        $birthDate = $profileData['birth_date'];
        $hometown = $profileData['hometown'];
        $bio = $profileData['bio'];
        
        include __DIR__ . '/../views/pages/profile/profile.php';
    }
    
    /**
     * Lấy thông tin profile của user
     * @param int $accountID
     * @return array|null
     */
    private function getProfile($accountID) {
        try {
            // Gọi stored procedure sp_GetUserProfile để lấy đầy đủ thông tin
            $profileModel = new Profile($accountID);
            $profileData = $profileModel->getProfile();
            
            if (!$profileData || !is_array($profileData) || count($profileData) === 0) {
                return null;
            }
            
            // Lấy dòng đầu tiên (kết quả từ stored procedure)
            $data = $profileData[0];
            
            // Map dữ liệu theo đúng tên cột từ stored procedure
            $profile = [
                'account_id' => $data['AccountID'] ?? $accountID,
                'username' => $data['Username'] ?? 'User',
                'email' => $data['Email'] ?? '',
                'full_name' => $data['FullName'] ?? $data['Username'] ?? 'User',
                'gender' => $data['Gender'] ?? 'Chưa cập nhật',
                'birth_date' => $data['BirthDate'] ?? '',
                'hometown' => $data['Hometown'] ?? 'Chưa cập nhật',
                'avatar' => $data['AvatarURL'] ?? '',
                'bio' => 'Chào mừng đến trang cá nhân của tôi!'
            ];
            
            return $profile;
            
        } catch (Exception $e) {
            error_log("ProfileController::getProfile() - Error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Lấy bài viết của user
     * @param int $accountID
     * @return array
     */
    private function getUserPosts($accountID) {
        try {
            $postModel = new Post($accountID);
            $postsFromDB = $postModel->getByUser();
            
            $posts = [];
            if ($postsFromDB && is_array($postsFromDB)) {
                require_once __DIR__ . '/../models/PostLike.php';
                require_once __DIR__ . '/../models/Comment.php';
                
                foreach ($postsFromDB as $row) {
                    $postID = $row['PostID'] ?? 0;
                    
                    // Đếm số lượng like
                    $likeCount = 0;
                    try {
                        $likeResult = $this->db->select(
                            "SELECT COUNT(*) as count FROM PostLike WHERE PostID = ?",
                            [$postID]
                        );
                        $likeCount = $likeResult[0]['count'] ?? 0;
                    } catch (Exception $e) {
                        // Ignore
                    }
                    
                    // Đếm số lượng comment
                    $commentCount = 0;
                    try {
                        $commentResult = $this->db->select(
                            "SELECT COUNT(*) as count FROM Comment WHERE PostID = ?",
                            [$postID]
                        );
                        $commentCount = $commentResult[0]['count'] ?? 0;
                    } catch (Exception $e) {
                        // Ignore
                    }
                    
                    $posts[] = [
                        'post_id' => $postID,
                        'content' => $row['Content'] ?? '',
                        'created_at' => $this->formatTimeAgo($row['CreatedAt'] ?? $row['PostTime'] ?? null),
                        'like_count' => $likeCount,
                        'comment_count' => $commentCount
                    ];
                }
            }
            
            return $posts;
            
        } catch (Exception $e) {
            error_log("ProfileController::getUserPosts() - Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy ảnh của user (từ bài viết)
     * @param int $accountID
     * @return array
     */
    private function getUserPhotos($accountID) {
        try {
            require_once __DIR__ . '/../models/Image.php';
            
            // Lấy posts của user
            $postModel = new Post($accountID);
            $posts = $postModel->getByUser(); // Sửa từ getByAuthor() thành getByUser()
            
            $photos = [];
            if ($posts && is_array($posts)) {
                foreach ($posts as $post) {
                    $postID = $post['PostID'] ?? 0;
                    if ($postID > 0) {
                        $imageModel = new Image($postID, '');
                        $images = $imageModel->getByPostId($postID);
                        
                        if ($images && is_array($images)) {
                            foreach ($images as $image) {
                                $photos[] = $image['url'] ?? '';
                            }
                        }
                    }
                }
            }
            
            return $photos;
            
        } catch (Exception $e) {
            error_log("ProfileController::getUserPhotos() - Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Format timestamp thành "X phút trước"
     */
    private function formatTimeAgo($timestamp) {
        if (!$timestamp) {
            return 'Vừa xong';
        }
        
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        
        $createdAt = strtotime($timestamp);
        $now = time();
        $diff = $now - $createdAt;
        
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
            return date('d/m/Y', $createdAt);
        }
    }
}


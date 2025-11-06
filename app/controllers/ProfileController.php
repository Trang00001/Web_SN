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
     * Hiển thị form chỉnh sửa hồ sơ
     */
    public function edit() {
        ensure_session_started();
        require_auth();

        $accountID = $_SESSION['user_id'] ?? null;
        if (!$accountID) { redirect('/login'); }

        $profile = $this->getProfile($accountID);
        if (!$profile) {
            // Nếu chưa có profile, dựng dữ liệu trống tối thiểu từ Account
            $account = (new Account())->getAccountById($accountID);
            $profile = [
                'account_id' => $accountID,
                'email' => $account['Email'] ?? '',
                'username' => $account['Username'] ?? 'User',
                'full_name' => '',
                'gender' => '',
                'birth_date' => '',
                'hometown' => '',
                'avatar' => ''
            ];
        }

        include __DIR__ . '/../views/pages/profile/edit_profile.php';
    }

    /**
     * Cập nhật hồ sơ người dùng
     */
    public function update() {
        ensure_session_started();
        require_auth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/profile/edit');
        }

        if (!check_csrf($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ. Vui lòng thử lại!';
            redirect('/profile/edit');
        }

        $accountID = $_SESSION['user_id'];

        // Lấy dữ liệu form
        $fullName = trim($_POST['full_name'] ?? '');
        $gender = trim($_POST['gender'] ?? '');
        $birthDate = trim($_POST['birth_date'] ?? '');
        $hometown = trim($_POST['hometown'] ?? '');

        // Chuẩn hóa birth date
        if ($birthDate === '') { $birthDate = null; }

        // Xử lý upload avatar (nếu có)
        $avatarUrl = null;
        if (!empty($_FILES['avatar']['name']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg','image/png','image/webp','image/jpg'];
            $mime = mime_content_type($_FILES['avatar']['tmp_name']);
            $size = (int)$_FILES['avatar']['size'];
            if (!in_array($mime, $allowed)) {
                $_SESSION['flash_error'] = 'Ảnh đại diện không hợp lệ.';
                redirect('/profile/edit');
            }
            if ($size > 2 * 1024 * 1024) { // 2MB
                $_SESSION['flash_error'] = 'Kích thước ảnh tối đa 2MB.';
                redirect('/profile/edit');
            }

            $uploadDir = __DIR__ . '/../../public/uploads/avatars';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }

            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $fileName = 'avatar_' . $accountID . '_' . time() . '.' . strtolower($ext);
            $targetPath = $uploadDir . '/' . $fileName;

            if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
                $_SESSION['flash_error'] = 'Không thể lưu ảnh đại diện.';
                redirect('/profile/edit');
            }

            // URL tương đối để hiển thị từ web
            $avatarUrl = '/uploads/avatars/' . $fileName;
        }

        // Lấy URL avatar hiện tại nếu không upload mới
        if ($avatarUrl === null) {
            $current = $this->getProfile($accountID);
            $avatarUrl = $current['avatar'] ?? '';
        }

        // Gọi model cập nhật
        $profileModel = new Profile($accountID, $fullName, $gender, $birthDate, $hometown, $avatarUrl);
        try {
            $profileModel->updateProfile();
            $_SESSION['flash_success'] = 'Cập nhật hồ sơ thành công!';
            redirect('/profile');
        } catch (Exception $e) {
            error_log('Update profile failed: ' . $e->getMessage());
            $_SESSION['flash_error'] = 'Cập nhật thất bại, vui lòng thử lại!';
            redirect('/profile/edit');
        }
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


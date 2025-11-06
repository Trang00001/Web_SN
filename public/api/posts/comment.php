<?php
/**
 * API Add Comment - Backend Endpoint
 * Thêm bình luận vào bài viết
 * 
 * @method POST
 * @requires Authentication (session user_id)
 * @input JSON: { "post_id": int, "content": string }
 * @output JSON: { "success": boolean, "comment": object }
 */

session_start();
require_once '../../../app/controllers/PostController.php';
require_once '../../../app/models/Account.php';
require_once '../../../core/Helpers.php';

// CORS headers - Allow cross-origin requests
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Validate HTTP method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed'
    ]);
    exit;
}

// Parse input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!$input) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid JSON'
    ]);
    exit;
}

$postID = (int)($input['post_id'] ?? 0);
$content = trim($input['content'] ?? '');

// Validate post_id
if ($postID <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'post_id không hợp lệ'
    ]);
    exit;
}

// Validate content
if (empty($content)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Nội dung bình luận không được để trống'
    ]);
    exit;
}

// Check content length
if (strlen($content) > 1000) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Bình luận quá dài (tối đa 1000 ký tự)'
    ]);
    exit;
}

// Check authentication
$userID = $_SESSION['user_id'] ?? null;

if (!$userID) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error' => 'Chưa đăng nhập'
    ]);
    exit;
}

// Add comment using PostController (includes notification creation)
try {
    $postController = new PostController();
    $result = $postController->addComment($postID, $userID, $content);
    
    if ($result['success']) {
        // Get user info from database for accurate data
        $account = new Account();
        $userData = $account->getAccountById($userID);
        
        $username = 'User';
        $avatar = '/public/assets/images/default-avatar.png';
        
        if ($userData) {
            $username = $userData['Username'] ?? $username;
            $avatar = $userData['Avatar'] ?? $avatar;
        }
        
        echo json_encode([
            'success' => true,
            'comment' => [
                'post_id' => $postID,
                'username' => $username,
                'avatar' => $avatar,
                'content' => $content,
                'created_at' => 'Vừa xong'
            ],
            'message' => $result['message'] ?? 'Đã thêm bình luận'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $result['error'] ?? 'Không thể thêm bình luận'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Lỗi server: ' . $e->getMessage()
    ]);
}
?>

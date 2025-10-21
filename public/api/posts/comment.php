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
require_once '../../../app/models/Comment.php';
require_once '../../../app/models/Account.php';

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

// Check authentication (AUTO-LOGIN FOR TESTING)
$userID = $_SESSION['user_id'] ?? null;

if (!$userID) {
    // Auto-login for testing
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'Alice';
    $_SESSION['email'] = 'alice@test.com';
    $userID = 1;
}

// Add comment
try {
    $comment = new Comment($postID, $userID, $content);
    $result = $comment->add();
    
    if ($result) {
        // Get user info for response
        $username = $_SESSION['username'] ?? 'User';
        $avatar = $_SESSION['avatar'] ?? '/public/assets/images/default-avatar.png';
        
        echo json_encode([
            'success' => true,
            'comment' => [
                'post_id' => $postID,
                'username' => $username,
                'avatar' => $avatar,
                'content' => $content,
                'created_at' => 'Vừa xong'
            ],
            'message' => 'Đã thêm bình luận'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Không thể lưu bình luận vào database'
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

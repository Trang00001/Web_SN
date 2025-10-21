<?php
/**
 * API Like/Unlike Post - Backend Endpoint
 * Xử lý like và unlike bài viết
 * 
 * @method POST
 * @requires Authentication (session user_id)
 * @input JSON: { "post_id": int, "action": "like"|"unlike" }
 * @output JSON: { "success": boolean, "new_count": int, "action": string }
 */

session_start();
require_once '../../../app/models/PostLike.php';

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
$action = trim($input['action'] ?? 'like');

// Validate post_id
if ($postID <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'post_id không hợp lệ'
    ]);
    exit;
}

// Validate action
if (!in_array($action, ['like', 'unlike'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'action phải là "like" hoặc "unlike"'
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

// Process like/unlike
try {
    $postLike = new PostLike($userID, $postID);
    
    if ($action === 'like') {
        // Add like
        $result = $postLike->like();
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'action' => 'liked',
                'message' => 'Đã thích bài viết'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Không thể thêm like vào database'
            ]);
        }
    } else {
        // Remove like
        $result = $postLike->unlike();
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'action' => 'unliked',
                'message' => 'Đã bỏ thích'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Không thể xóa like khỏi database'
            ]);
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Lỗi server: ' . $e->getMessage()
    ]);
}
?>

<?php
/**
 * API Delete Post
 * Xóa bài viết thuộc về người dùng hiện tại
 *
 * @method POST
 * @requires Authentication (session user_id)
 * @input JSON: { "post_id": int }
 * @output JSON: { "success": boolean, "message"?: string, "error"?: string }
 */

// Bắt đầu output buffering để tránh output trước header
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

session_start();

require_once '../../../app/controllers/PostController.php';

// Clear any output buffer
ob_clean();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed'
    ]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!is_array($input)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid JSON'
    ]);
    exit;
}

$postId = (int)($input['post_id'] ?? 0);
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error' => 'Chưa đăng nhập'
    ]);
    exit;
}

$controller = new PostController();
$result = $controller->deletePost($postId, $userId);

if (!$result['success']) {
    $error = $result['error'] ?? 'Không thể xóa bài viết';
    $statusCode = str_contains($error, 'quyền') ? 403 : 400;
    http_response_code($statusCode);
    echo json_encode([
        'success' => false,
        'error' => $error
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => $result['message'] ?? 'Đã xóa bài viết'
]);
exit;
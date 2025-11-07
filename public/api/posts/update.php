<?php
/**
 * API Update Post
 * Cho phép chủ sở hữu chỉnh sửa nội dung bài viết
 *
 * @method POST
 * @requires Authentication (session user_id)
 * @input JSON: { "post_id": int, "content": string }
 * @output JSON: { "success": boolean, "message"?: string, "error"?: string }
 */

session_start();

require_once '../../../app/controllers/PostController.php';

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
$content = $input['content'] ?? '';

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
$result = $controller->updatePost($postId, $userId, $content);

if (!$result['success']) {
    $error = $result['error'] ?? 'Không thể cập nhật bài viết';
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
    'message' => $result['message'] ?? 'Đã cập nhật bài viết',
    'updated_content' => $content
]);


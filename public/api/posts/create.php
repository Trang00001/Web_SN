<?php
/**
 * API tạo bài viết - PROTOTYPE VERSION
 * Đơn giản để dễ điều chỉnh sau này
 */

session_start();

// Response đơn giản
header('Content-Type: application/json');

// Chỉ kiểm tra cơ bản
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$content = trim($input['content'] ?? '');

if (empty($content)) {
    echo json_encode(['error' => 'Nội dung không được để trống']);
    exit;
}

// Tạo bài viết đơn giản - chỉ lưu trong session
$newPost = [
    'post_id' => time(),
    'username' => $_SESSION['username'] ?? 'Demo User',
    'content' => $content,
    'created_at' => 'Vừa xong',
    'like_count' => 0,
    'comment_count' => 0
];

// Lưu vào session (tạm thời)
if (!isset($_SESSION['new_posts'])) {
    $_SESSION['new_posts'] = [];
}
array_unshift($_SESSION['new_posts'], $newPost);

echo json_encode(['success' => true, 'post' => $newPost]);
?>
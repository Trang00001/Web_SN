<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit;
}

require_once __DIR__ . '/../../../core/Database.php';

try {
    $db = new Database();
    $data = json_decode(file_get_contents('php://input'), true);
    
    $accountId = $_SESSION['user_id'];
    $postId = $data['post_id'] ?? null;
    
    if (!$postId) {
        throw new Exception('Thiếu ID bài viết');
    }
    
    // Kiểm tra đã lưu chưa
    $check = $db->select(
        "SELECT * FROM SavedPost WHERE AccountID = ? AND PostID = ?",
        [$accountId, $postId]
    );
    
    if ($check && count($check) > 0) {
        // Đã lưu -> Bỏ lưu
        $db->execute(
            "DELETE FROM SavedPost WHERE AccountID = ? AND PostID = ?",
            [$accountId, $postId]
        );
        echo json_encode(['success' => true, 'saved' => false, 'message' => 'Đã bỏ lưu']);
    } else {
        // Chưa lưu -> Lưu
        $db->execute(
            "INSERT INTO SavedPost (AccountID, PostID, SavedTime) VALUES (?, ?, NOW())",
            [$accountId, $postId]
        );
        echo json_encode(['success' => true, 'saved' => true, 'message' => 'Đã lưu bài viết']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

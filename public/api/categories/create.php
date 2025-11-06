<?php
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    ob_clean();
    header('Content-Type: application/json');
    die(json_encode(['success' => false, 'message' => 'Chưa đăng nhập']));
}

// Chỉ cho phép POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_clean();
    header('Content-Type: application/json');
    die(json_encode(['success' => false, 'message' => 'Method không hợp lệ']));
}

try {
    // Đọc dữ liệu JSON
    $input = json_decode(file_get_contents('php://input'), true);
    $categoryName = trim($input['name'] ?? '');

    // Validate
    if (empty($categoryName)) {
        ob_clean();
        header('Content-Type: application/json');
        die(json_encode(['success' => false, 'message' => 'Tên không được để trống']));
    }

    if (strlen($categoryName) > 50) {
        ob_clean();
        header('Content-Type: application/json');
        die(json_encode(['success' => false, 'message' => 'Tên quá dài']));
    }

    // Kết nối database
    require_once __DIR__ . '/../../../core/Database.php';
    $dbInstance = new Database();
    $db = $dbInstance->getConnection();

    // Kiểm tra trùng tên
    $checkStmt = $db->prepare("SELECT CategoryID FROM PostCategory WHERE CategoryName = ?");
    $checkStmt->execute([$categoryName]);
    if ($checkStmt->fetch()) {
        ob_clean();
        header('Content-Type: application/json');
        die(json_encode(['success' => false, 'message' => 'Danh mục đã tồn tại']));
    }

    // Thêm danh mục mới
    $stmt = $db->prepare("INSERT INTO PostCategory (CategoryName) VALUES (?)");
    $stmt->execute([$categoryName]);

    ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Tạo thành công',
        'category_id' => $db->lastInsertId()
    ]);

} catch (Exception $e) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
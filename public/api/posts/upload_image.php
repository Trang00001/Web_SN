<?php
/**
 * API Upload Image
 * Upload ảnh lên server và trả về URL
 * 
 * @method POST
 * @requires Authentication
 * @input FormData with file
 * @output JSON: { "success": boolean, "image_url": string }
 */

session_start();
header('Content-Type: application/json');

// Check authentication (AUTO-LOGIN FOR TESTING)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'Alice';
    $_SESSION['email'] = 'alice@test.com';
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Không có file được upload']);
    exit;
}

$file = $_FILES['image'];

// Validate file type
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedTypes)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Chỉ chấp nhận file ảnh (JPEG, PNG, GIF, WebP)']);
    exit;
}

// Validate file size (max 5MB)
if ($file['size'] > 5 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'File quá lớn (tối đa 5MB)']);
    exit;
}

// Create upload directory if not exists
$uploadDir = __DIR__ . '/../../uploads/posts/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid() . '_' . time() . '.' . $extension;
$filepath = $uploadDir . $filename;

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $filepath)) {
    // Return relative URL that works with any setup
    // Path from web root: /uploads/posts/filename.jpg
    $imageURL = '/uploads/posts/' . $filename;
    
    echo json_encode([
        'success' => true,
        'image_url' => $imageURL,
        'filename' => $filename
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Không thể lưu file']);
}
?>

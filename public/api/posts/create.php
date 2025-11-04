<?php
/**
 * API Create Post - Backend Endpoint
 * Lưu bài viết vào database thông qua Post Model
 * 
 * @method POST
 * @requires Authentication (session user_id)
 * @input JSON: { "content": string, "media_url": string (optional) }
 * @output JSON: { "success": boolean, "post_id": int, "message": string }
 */

session_start();
require_once '../../../app/models/Post.php';
require_once '../../../app/models/Image.php';

// Set response header
header('Content-Type: application/json');

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

$content = trim($input['content'] ?? '');
$imageURLs = $input['image_urls'] ?? []; // Array of uploaded image URLs
$categoryID = (int)($input['category_id'] ?? 1); // Default category = 1

// Validate content
if (empty($content)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Nội dung không được để trống'
    ]);
    exit;
}

// Check content length
if (strlen($content) > 5000) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Nội dung quá dài (tối đa 5000 ký tự)'
    ]);
    exit;
}

// Check authentication (AUTO-LOGIN FOR TESTING)
$authorID = $_SESSION['user_id'] ?? null;

if (!$authorID) {
    // Auto-login for testing
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'Alice';
    $_SESSION['email'] = 'alice@test.com';
    $authorID = 1;
}

// Create post object
try {
    $post = new Post($authorID, $content, null, $categoryID);
    
    // Save to database via stored procedure
    $result = $post->create();
    
    if ($result) {
        // Get the created post ID
        $postID = $post->getPostID();
        
        // Add images if provided
        $imageCount = 0;
        if (!empty($imageURLs) && is_array($imageURLs)) {
            foreach ($imageURLs as $imageURL) {
                $image = new Image($postID, trim($imageURL));
                if ($image->addImage()) {
                    $imageCount++;
                }
            }
        }
        
        echo json_encode([
            'success' => true,
            'post_id' => $postID,
            'image_count' => $imageCount,
            'message' => 'Đăng bài thành công'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Không thể lưu bài viết vào database'
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
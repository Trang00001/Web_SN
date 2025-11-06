<?php
/**
 * Right Sidebar - Simple & Clean
 */
$userId = $_SESSION['user_id'] ?? 1;

// Get real user data from database
$currentUser = 'User';
$fullName = '';
$avatarURL = '';
$postCount = 0;
$friendCount = 0;

try {
    // Database class should be already loaded
    if (!class_exists('Database')) {
        require_once __DIR__ . '/../../../../core/Database.php';
    }
    $db = new Database();
    
    // Get user info from Account and Profile
    $userResult = $db->select(
        "SELECT a.Username, p.FullName, p.AvatarURL 
         FROM Account a 
         LEFT JOIN Profile p ON a.AccountID = p.AccountID 
         WHERE a.AccountID = ?", 
        [$userId]
    );
    
    if ($userResult && isset($userResult[0])) {
        $currentUser = $userResult[0]['Username'] ?? 'User';
        $fullName = $userResult[0]['FullName'] ?? '';
        $avatarURL = $userResult[0]['AvatarURL'] ?? '';
    }
    
    // Count user's posts
    $postResult = $db->select("SELECT COUNT(*) as count FROM Post WHERE AuthorID = ?", [$userId]);
    if ($postResult && isset($postResult[0]['count'])) {
        $postCount = $postResult[0]['count'];
    }
    
    // Count user's friends (Friendship table doesn't have Status column)
    $friendResult = $db->select(
        "SELECT COUNT(*) as count FROM Friendship 
         WHERE Account1ID = ? OR Account2ID = ?", 
        [$userId, $userId]
    );
    if ($friendResult && isset($friendResult[0]['count'])) {
        $friendCount = $friendResult[0]['count'];
    }
} catch (Exception $e) {
    error_log("Sidebar stats error: " . $e->getMessage());
}

// Format numbers (K for thousands)
function formatNumber($num) {
    if ($num >= 1000) {
        return round($num / 1000, 1) . 'K';
    }
    return $num;
}

// Display name priority: FullName > Username
$displayName = !empty($fullName) ? $fullName : $currentUser;
$displayInitial = strtoupper(substr($displayName, 0, 1));
?>

<!-- User Profile Section -->
<div class="tech-card user-profile">
    <div class="user-avatar-lg mx-auto mb-3">
        <?php if (!empty($avatarURL)): ?>
            <img src="<?= htmlspecialchars($avatarURL) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
        <?php else: ?>
            <?= $displayInitial ?>
        <?php endif; ?>
    </div>
    <h5 class="text-center mb-2"><?= htmlspecialchars($displayName) ?></h5>
    <p class="text-center text-muted mb-3">@<?= htmlspecialchars($currentUser) ?></p>
    
    <!-- User Stats - Real Data -->
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-number"><?= formatNumber($postCount) ?></span>
            <small>Bài viết</small>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= formatNumber($friendCount) ?></span>
            <small>Bạn bè</small>
        </div>
    </div>
</div>

<!-- Thống kê nhanh -->
<div class="tech-card">
    <h6 class="mb-3 fw-bold">
        <i class="fas fa-chart-line me-2"></i>
        Thống kê hôm nay
    </h6>
    
    <?php
    try {
        $db = new Database();
        
        // Đếm bài viết hôm nay
        $today = date('Y-m-d');
        $postsToday = $db->select(
            "SELECT COUNT(*) as total FROM Post WHERE DATE(PostTime) = ?",
            [$today]
        );
        $postCount = $postsToday[0]['total'] ?? 0;
        
        // Đếm comment hôm nay
        $commentsToday = $db->select(
            "SELECT COUNT(*) as total FROM Comment WHERE DATE(CommentTime) = ?",
            [$today]
        );
        $commentCount = $commentsToday[0]['total'] ?? 0;
        
        echo '<div class="d-flex justify-content-between mb-2">';
        echo '<span class="small">Bài viết mới</span>';
        echo '<strong class="text-primary">' . $postCount . '</strong>';
        echo '</div>';
        
        echo '<div class="d-flex justify-content-between mb-2">';
        echo '<span class="small">Bình luận</span>';
        echo '<strong class="text-success">' . $commentCount . '</strong>';
        echo '</div>';
        
        echo '<div class="d-flex justify-content-between">';
        echo '<span class="small">Người dùng</span>';
        $totalUsers = $db->select("SELECT COUNT(*) as total FROM Account", []);
        echo '<strong class="text-info">' . ($totalUsers[0]['total'] ?? 0) . '</strong>';
        echo '</div>';
        
    } catch (Exception $e) {
        echo '<p class="text-muted small mb-0">Không thể tải thống kê</p>';
    }
    ?>
</div>
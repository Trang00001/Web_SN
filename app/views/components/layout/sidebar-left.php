<?php
/**
 * Left Sidebar Component
 * Chứa user profile và menu navigation
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
    
    // Count user's friends
    $friendResult = $db->select("SELECT COUNT(*) as count FROM Friendship WHERE (AccountID1 = ? OR AccountID2 = ?) AND Status = 'accepted'", [$userId, $userId]);
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

<!-- Navigation Menu -->
<div class="tech-card">
    <h6 class="mb-3 fw-bold">Menu chính</h6>
    
    <a href="/home" class="menu-item">
        <i class="fas fa-home"></i>
        <span>Trang chủ</span>
    </a>
    
    <a href="/friends" class="menu-item">
        <i class="fas fa-users"></i>
        <span>Bạn bè</span>
    </a>
    
    <a href="/messages" class="menu-item">
        <i class="fas fa-envelope"></i>
        <span>Tin nhắn</span>
    </a>
    
    <a href="/notifications" class="menu-item">
        <i class="fas fa-bell"></i>
        <span>Thông báo</span>
    </a>
    
    <a href="/search" class="menu-item">
        <i class="fas fa-compass"></i>
        <span>Khám phá</span>
    </a>
    
    <a href="#" class="menu-item">
        <i class="fas fa-bookmark"></i>
        <span>Đã lưu</span>
    </a>
    
    <a href="/profile" class="menu-item">
        <i class="fas fa-cog"></i>
        <span>Cài đặt</span>
    </a>
</div>
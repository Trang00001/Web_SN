<?php
/**
 * Create Post Component
 * Component tạo bài viết mới
 */

// Use user info from parent file (home.php)
// Variables: $currentUser, $userInitial, $userAvatar are already set
?>

<div class="create-post-card">
    <div class="d-flex align-items-center mb-4">
        <div class="user-avatar me-3" style="overflow: hidden;">
            <?php if (!empty($userAvatar)): ?>
                <img src="<?= htmlspecialchars($userAvatar) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            <?php else: ?>
                <?= $userInitial ?>
            <?php endif; ?>
        </div>
        <div class="flex-grow-1">
            <input type="text" 
                   class="form-control create-post-input" 
                   placeholder="Bạn đang nghĩ gì, <?= htmlspecialchars($currentUser) ?>?"
                   data-bs-toggle="modal" 
                   data-bs-target="#createPostModal"
                   readonly>
        </div>
    </div>
</div>

<!-- Modal được define trong home.php -->
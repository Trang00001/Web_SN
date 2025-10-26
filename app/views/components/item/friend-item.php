<?php
// friend-item.php - component hiển thị 1 item friend/request

// Đảm bảo biến bắt buộc tồn tại, nếu không có thì gán mặc định
$tab = $tab ?? 'all';           // 'all' | 'suggested' | 'requests'
$friendData = $friendData ?? null; // dữ liệu friend/suggested: AccountID, Username, AvatarURL
$req = $req ?? null;            // dữ liệu request: RequestID, SenderName, SenderID, AvatarURL

// Avatar mặc định nếu không có
$defaultAvatar = '/Web_SN/public/assets/images/default-avatar.png';
?>

<div class="col-md-6 mb-3">
<?php if ($tab === 'all' && $friendData): ?>
    <div class="card p-3 friend-item d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <img src="<?= htmlspecialchars($friendData['AvatarURL'] ?? $defaultAvatar) ?>" 
                 alt="Avatar" class="rounded-circle me-3" width="50" height="50">
            <span class="fw-bold"><?= htmlspecialchars($friendData['Username'] ?? '') ?></span>
        </div>
        <button class="btn btn-sm btn-danger btn-remove-friend" data-id="<?= $friendData['AccountID'] ?>">
            Xóa bạn
        </button>
    </div>
<?php endif; ?>


<!-- <php elseif ($tab === 'suggested' && $friendData): ?>
    <div class="card p-3 friend-item">
        <div class="d-flex align-items-center">
            <img src="<= htmlspecialchars($friendData['AvatarURL'] ?? $defaultAvatar) ?>" 
                 alt="Avatar" class="rounded-circle me-3" width="50" height="50">
            <span class="fw-bold"><= htmlspecialchars($friendData['Username'] ?? '') ?></span>
        </div>
        <div class="mt-2">
            <button class="btn btn-sm btn-primary btn-send-request" data-id="<= $friendData['AccountID'] ?? 0 ?>">
                Kết bạn
            </button>
        </div>
    </div> -->

<?php if ($tab === 'requests' && $req): ?>
    <div class="card p-3 friend-item">
        <div class="d-flex align-items-center">
            <img src="<?= htmlspecialchars($req['AvatarURL'] ?? $defaultAvatar) ?>" 
                 alt="Avatar" class="rounded-circle me-3" width="50" height="50">
            <span class="fw-bold"><?= htmlspecialchars($req['SenderName'] ?? '') ?></span>
        </div>
        <div class="mt-2 d-flex gap-2">
            <button class="btn btn-sm btn-success btn-accept-request" data-id="<?= $req['RequestID'] ?? 0 ?>">
                Chấp nhận
            </button>
            <button class="btn btn-sm btn-danger btn-reject-request" data-id="<?= $req['RequestID'] ?? 0 ?>">
                Từ chối
            </button>
        </div>
    </div>
<?php endif; ?>
</div>
<?php
// requests.php
$tab = 'requests'; // bắt buộc
$currentUserID = $currentUserID ?? 1; // user hiện tại
$requests = $requests ?? []; // mảng lời mời đến
$defaultAvatar = '/Web_SN/assets/images/default-avatar.png';
?>

<div class="row g-3">
<?php foreach ($requests as $r): ?>
    <?php 
    // Chuẩn dữ liệu để friend-item.php dùng
    $req = [
        'RequestID'  => $r['RequestID'],
        'SenderName' => $r['SenderName'],
        'SenderID'   => $r['SenderID'],
        'AvatarURL'  => $r['AvatarURL'] ?? $defaultAvatar,
        'type'       => 'request'
    ]; 
    ?>
    <div class="col-md-6 friend-item" data-request-id="<?= $r['RequestID'] ?>">
        <?php include __DIR__ . '/../../components/item/friend-item.php'; ?>
    </div>
<?php endforeach; ?>
</div>
<?php if (!isset($msg)) return; ?>

<div class="d-flex mb-3 <?= ($msg['SenderID'] == $userID) ? 'justify-content-end' : '' ?>"
     data-messageid="<?= $msg['MessageID'] ?>">
    <!-- Avatar (chỉ hiện với tin nhắn đối tác) -->
    <?php if ($msg['SenderID'] != $userID): ?>
        <img src="<?= htmlspecialchars($msg['AvatarURL'] ?? 'https://i.pravatar.cc/40') ?>"
             alt=""
             class="rounded-circle me-2"
             width="40" height="40">
    <?php endif; ?>

    <!-- Nội dung -->
    <div>
        <div class="<?= ($msg['SenderID'] == $userID) ? 'bg-primary text-white' : 'bg-white' ?> p-2 rounded shadow-sm">
            <?= htmlspecialchars($msg['Content'] ?? '') ?>
        </div>
        <small class="text-muted d-block <?= ($msg['SenderID'] == $userID) ? 'text-end' : '' ?>">
            <?= htmlspecialchars($msg['SentTime'] ?? '') ?>
        </small>
    </div>
</div>

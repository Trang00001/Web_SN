<?php
/* Hiển thị avatar + tên.
   Biến đầu vào: $user (array|object) có tối thiểu: id, name, email, avatar (đường dẫn ảnh) */
$avatar = !empty($user['avatar'] ?? $user->avatar ?? '') ? ($user['avatar'] ?? $user->avatar) : '/assets/img/avatar-default.png';
$name   = htmlspecialchars($user['name'] ?? $user->name ?? 'Người dùng');
$email  = htmlspecialchars($user['email'] ?? ($user->getEmail() ?? ''));
?>
<link rel="stylesheet" href="/assets/css/profile.css">
<div class="profile-card card shadow-sm">
  <div class="card-body text-center">
    <img class="avatar mb-3" src="<?=$avatar?>" alt="avatar">
    <h5 class="mb-1"><?=$name?></h5>
    <?php if ($email): ?><div class="text-muted small"><?=$email?></div><?php endif; ?>
  </div>
</div>

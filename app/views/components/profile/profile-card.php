<?php
// app/views/components/profile/profile-card.php
// Inputs (optional): $name, $email, $avatar
$name  = $name  ?? 'Người dùng mới';
$email = $email ?? 'user@example.com';
$avatar = $avatar ?? 'https://i.pravatar.cc/150';
?>
<div class="card profile-card shadow-sm rounded-4">
  <div class="card-body d-flex align-items-center gap-3">
    <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar" class="rounded-circle object-fit-cover" width="72" height="72">
    <div>
      <h2 class="h5 mb-1"><?= htmlspecialchars($name) ?></h2>
      <p class="text-muted mb-0"><i class="fa-regular fa-envelope me-2"></i><?= htmlspecialchars($email) ?></p>
    </div>
  </div>
</div>

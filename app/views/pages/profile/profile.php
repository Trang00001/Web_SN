<?php
/* Trang xem hồ sơ cơ bản: tên, email, avatar.
   Controller nên truyền $user (mảng/obj) vào view này. */
?>
<link rel="stylesheet" href="/assets/css/profile.css">

<div class="container my-4">
  <div class="row g-4">
    <div class="col-md-4">
      <?php include __DIR__ . '/../../components/profile/profile-card.php'; ?>
    </div>
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="mb-3">Thông tin cá nhân</h4>
          <dl class="row">
            <dt class="col-sm-3">Họ & tên</dt>
            <dd class="col-sm-9"><?=htmlspecialchars($user['name'] ?? $user->name ?? '')?></dd>

            <dt class="col-sm-3">Email</dt>
            <dd class="col-sm-9"><?=htmlspecialchars($user['email'] ?? ($user->getEmail() ?? ''))?></dd>

            <dt class="col-sm-3">Username</dt>
            <dd class="col-sm-9"><?=htmlspecialchars($user['username'] ?? ($user->getUsername() ?? ''))?></dd>
          </dl>

          <a href="/profile/edit" class="btn btn-outline-primary">Chỉnh sửa hồ sơ</a>
        </div>
      </div>
    </div>
  </div>
</div>

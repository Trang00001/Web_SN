<?php
require_once __DIR__ . '/../../../../core/Helpers.php';
require_auth();

// Expect variables from controller when rendering this view
// $profile: [account_id, email, username, full_name, gender, birth_date, hometown, avatar]

$displayName = $profile['full_name'] ?? ($profile['username'] ?? 'User');
$displayEmail = $profile['email'] ?? '';
$displayAvatar = $profile['avatar'] ?? '';
$fullName = $profile['full_name'] ?? '';
$gender = $profile['gender'] ?? '';
$birthDate = $profile['birth_date'] ?? '';
$hometown = $profile['hometown'] ?? '';

// Convert birth date to yyyy-mm-dd for input[type=date]
if (!empty($birthDate)) {
    $ts = strtotime($birthDate);
    if ($ts) { $birthDate = date('Y-m-d', $ts); }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chỉnh sửa hồ sơ - <?= htmlspecialchars($displayName) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container py-4" style="max-width: 760px;">
    <h3 class="mb-3">Chỉnh sửa hồ sơ</h3>

    <?php if (!empty($_SESSION['flash_success'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
      <?php unset($_SESSION['flash_success']); endif; ?>
    <?php if (!empty($_SESSION['flash_error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
      <?php unset($_SESSION['flash_error']); endif; ?>

    <form action="<?= htmlspecialchars(url('/profile/edit')) ?>" method="post" enctype="multipart/form-data" class="card p-3 shadow-sm">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">

      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">Họ và tên</label>
          <input type="text" name="full_name" value="<?= htmlspecialchars($fullName) ?>" class="form-control" maxlength="100">
        </div>
        <div class="col-md-6">
          <label class="form-label">Giới tính</label>
          <select name="gender" class="form-select">
            <option value="" <?= $gender===''? 'selected':'' ?>>Chưa cập nhật</option>
            <option value="Male" <?= $gender==='Male'? 'selected':'' ?>>Male</option>
            <option value="Female" <?= $gender==='Female'? 'selected':'' ?>>Female</option>
            <option value="Other" <?= $gender==='Other'? 'selected':'' ?>>Other</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Ngày sinh</label>
          <input type="date" name="birth_date" value="<?= htmlspecialchars($birthDate) ?>" class="form-control">
        </div>
        <div class="col-12">
          <label class="form-label">Địa điểm</label>
          <input type="text" name="hometown" value="<?= htmlspecialchars($hometown) ?>" class="form-control" maxlength="100">
        </div>
        <div class="col-12">
          <label class="form-label">Ảnh đại diện</label>
          <?php if ($displayAvatar): ?>
            <div class="mb-2">
              <img src="<?= htmlspecialchars($displayAvatar) ?>" alt="avatar" style="width:80px;height:80px;object-fit:cover;border-radius:50%" onerror="this.style.display='none'">
            </div>
          <?php endif; ?>
          <input type="file" name="avatar" accept="image/*" class="form-control">
          <div class="form-text">Tối đa 2MB. Định dạng: jpg, jpeg, png, webp.</div>
        </div>
      </div>

      <div class="d-flex gap-2 mt-3">
        <a href="<?= htmlspecialchars(url('/profile')) ?>" class="btn btn-outline-secondary">Hủy</a>
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
      </div>
    </form>
  </div>
</body>
</html>


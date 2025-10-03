<?php
// app/views/layouts/main.php
?><!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Social Network') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <?php if(!empty($styles)): foreach($styles as $s): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($s) ?>">
  <?php endforeach; endif; ?>
</head>
<body>
  <?php include __DIR__ . '/../components/layout/navbar.php'; ?>
  <main class="container py-4">
    <?php echo $content ?? ''; ?>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= $BASE ?>/public/assets/js/validation.js"></script>
  <?php if(!empty($scripts)): foreach($scripts as $js): ?>
    <script src="<?= htmlspecialchars($js) ?>"></script>
  <?php endforeach; endif; ?>
</body>
</html>

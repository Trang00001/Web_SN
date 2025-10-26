<?php

// main.php - Layout dùng chung
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title><?php echo $title ?? "MySocial"; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap + FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- CSS Layout -->
  <link rel="stylesheet" href="<?php echo htmlspecialchars(defined('ASSETS_URL') ? ASSETS_URL : '/assets'); ?>/css/variables.css">
  <link rel="stylesheet" href="<?php echo htmlspecialchars(defined('ASSETS_URL') ? ASSETS_URL : '/assets'); ?>/css/layout.css">

    
</head>
<body>
  <?php include __DIR__ . '/../components/layout/navbar.php'; ?>

  <main class="container my-4">
    <?php echo $content ?? ''; ?>
  </main>

  <?php include __DIR__ . '/../components/layout/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo htmlspecialchars(defined('ASSETS_URL') ? ASSETS_URL : '/assets'); ?>/js/app.js"></script>


</body>
</html>

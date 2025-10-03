<?php
// layouts/main.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . " - MySocial" : "MySocial"; ?></title>
    <meta name="description" content="MySocial - Mạng xã hội kết nối bạn bè và chia sẻ khoảnh khắc.">
    <link rel="icon" href="/SN_Web/public/assets/img/favicon.png" type="image/png">

    <!-- Bootstrap CSS & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/SN_Web/public/assets/css/global.css">
    <link rel="stylesheet" href="/SN_Web/public/assets/css/bootstrap-custom.css">
</head>
<body class="main-page">

    <!-- Navbar -->
    <?php include __DIR__ . '/../components/layout/navbar.php'; ?>

    <!-- Flash messages -->
    <?php if (!empty($_SESSION['flash_message'])): ?>
        <div class="container mt-3">
            <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info'; ?>">
                <?= $_SESSION['flash_message']; ?>
            </div>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>

    <!-- Content -->
    <main id="app-content" class="container mt-4">
        <?= $content ?? ''; ?>
    </main>

    <!-- Footer -->
    <?php include __DIR__ . '/../components/layout/footer.php'; ?>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/SN_Web/public/assets/js/main.js" defer></script>
    <script src="/SN_Web/public/assets/js/app.js" defer></script>
</body>
</html>

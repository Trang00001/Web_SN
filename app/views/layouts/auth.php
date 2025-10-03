<?php
// layouts/auth.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . " - MySocial" : "MySocial Auth"; ?></title>
    <meta name="description" content="Đăng nhập hoặc đăng ký MySocial để kết nối bạn bè.">
    <link rel="icon" href="/SN_Web/public/assets/img/favicon.png" type="image/png">

    <!-- Bootstrap CSS & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/SN_Web/public/assets/css/global.css">
    <link rel="stylesheet" href="/SN_Web/public/assets/css/bootstrap-custom.css">
</head>
<body class="auth-page">

    <main class="auth-container">
        <?php if (isset($content)) echo $content; ?>
    </main>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/SN_Web/public/assets/js/main.js" defer></script>
</body>
</html>

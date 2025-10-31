<?php
session_start();
ob_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bạn bè - Social Network</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    #friend-nav { background-color: #fff; border-right:1px solid #dee2e6; min-height:80vh; }
    #friend-content { background-color: #f1f3f5; padding:20px; min-height:80vh; }
  </style>
</head>
<body>
<div class="container-fluid mt-4">
  <div class="row">
    <div class="col-lg-3 mb-3">
      <div class="list-group" id="friend-nav">
        <button class="list-group-item list-group-item-action active" data-tab="all">Tất cả bạn bè</button>
        <button class="list-group-item list-group-item-action" data-tab="suggested">Gợi ý kết bạn</button>
        <button class="list-group-item list-group-item-action" data-tab="requests">Lời mời kết bạn</button>
      </div>
    </div>

    <div class="col-lg-9" id="friend-content">
      <div class="text-center text-muted py-5">
        Chọn mục bên trái để xem nội dung.
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/public/assets/js/friends.js"></script>
</body>
</html>
<?php
// Lấy nội dung buffer
$content = ob_get_clean();

// Áp dụng layout
require_once __DIR__ . '/../../layouts/main.php';
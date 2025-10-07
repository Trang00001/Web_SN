<?php
$id = $_GET['id'] ?? 1;

// Dữ liệu demo cho từng người
$users = [
  1 => ["name" => "Nguyễn Văn A", "avatar" => "https://i.pravatar.cc/40?img=1"],
  2 => ["name" => "Trần Thị B", "avatar" => "https://i.pravatar.cc/40?img=2"],
  3 => ["name" => "Lê Văn C", "avatar" => "https://i.pravatar.cc/40?img=3"]
];

$user = $users[$id];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Chat với <?= htmlspecialchars($user['name']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
  <h3 class="mb-4">Cuộc trò chuyện với <?= htmlspecialchars($user['name']) ?></h3>

  <div class="card shadow-sm">
    <!-- Khung chat -->
    <div class="card-body" id="chat-box" style="height: 400px; overflow-y: auto; background-color: #f8f9fa;">
      <?php
      include __DIR__ . "/../../components/item/message-item.php";
      renderMessage($user['avatar'], "Xin chào!", "10:30 AM", false);
      renderMessage("https://i.pravatar.cc/40?img=5", "Chào bạn!", "10:32 AM", true);
      ?>
    </div>

    <!-- Ô nhập tin nhắn -->
    <div class="card-footer">
      <div class="input-group">
        <input id="message-input" type="text" class="form-control" placeholder="Nhập tin nhắn...">
        <button id="send-btn" class="btn btn-primary">Gửi</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/Web_SN/public/assets/js/messages.js"></script>
</body>
</html>

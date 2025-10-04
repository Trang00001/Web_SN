<?php
$title = "Tin nhắn";
ob_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Danh sách tin nhắn</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
  <h3 class="mb-4">Tin nhắn</h3>
  
  <div class="card shadow-sm">
    <div class="card-body p-0">

      <?php
      // Giả lập dữ liệu danh sách cuộc trò chuyện
      $conversations = [
          ["id" => 1, "avatar" => "https://i.pravatar.cc/40?img=1", "name" => "Nguyễn Văn A", "last" => "Xin chào, bạn có rảnh không?", "time" => "10:30 AM"],
          ["id" => 2, "avatar" => "https://i.pravatar.cc/40?img=2", "name" => "Trần Thị B", "last" => "Hôm nay học thế nào rồi?", "time" => "11:15 AM"],
          ["id" => 3, "avatar" => "https://i.pravatar.cc/40?img=3", "name" => "Lê Văn C", "last" => "Nhớ gửi mình file bài tập nhé!", "time" => "12:05 PM"]
      ];

      foreach ($conversations as $conv): ?>
        <a href="chat.php?id=<?= $conv['id'] ?>" class="text-decoration-none text-dark">
          <div class="d-flex align-items-start p-2 border-bottom bg-white conversation-item">
              <img src="<?= $conv['avatar'] ?>" class="rounded-circle me-3" width="40" height="40" alt="<?= $conv['name'] ?>">
              <div class="flex-grow-1">
                  <div class="d-flex justify-content-between">
                      <strong><?= $conv['name'] ?></strong>
                      <small class="text-muted"><?= $conv['time'] ?></small>
                  </div>
                  <p class="mb-0 text-muted"><?= $conv['last'] ?></p>
              </div>
          </div>
        </a>
      <?php endforeach; ?>

    </div>
  </div>
</div>

</body>
</html>
<?php
$content = ob_get_clean();
require_once __DIR__ .'/../../layouts/main.php';

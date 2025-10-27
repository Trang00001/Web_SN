<?php
$title = "Tin nhắn";
ob_start();
require_once __DIR__ . '/../../../models/ChatBox.php';
session_start();
$userID = $_SESSION['userID'] ?? 1;

$chatBox = new ChatBox(0,0);
$chatList = $chatBox->getChatList($userID);
?>

<div class="container py-4">
  <h3 class="mb-4">Tin nhắn</h3>
  
  <?php if(!empty($chatList)): ?>
    <div class="list-group">
      <?php foreach ($chatList as $chat):
          $otherID = ($chat['Account1ID'] == $userID) ? $chat['Account2ID'] : $chat['Account1ID'];
          $otherName = $chat['Username'] ?? 'Người dùng';
          $otherAvatar = $chat['AvatarURL'] ?? 'https://i.pravatar.cc/40';
          $lastMessage = $chat['LastMessage'] ?? '';
          $lastTime = $chat['LastMessageTime'] ?? '';
      ?>
      <a href="chat.php?id=<?= $chat['ChatBoxID'] ?>" 
         class="list-group-item list-group-item-action d-flex align-items-center">
          <img src="<?= $otherAvatar ?>" class="rounded-circle me-3" width="40" height="40">
          <div class="flex-grow-1">
              <div class="d-flex justify-content-between">
                  <strong><?= $otherName ?></strong>
                  <small class="text-muted"><?= $lastTime ?></small>
              </div>
              <p class="mb-0 text-muted"><?= $lastMessage ?></p>
          </div>
      </a>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>Chưa có cuộc trò chuyện nào.</p>
  <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ .'/../../layouts/main.php';

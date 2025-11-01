<?php
$title = "Chi tiết chat";
ob_start();
// session_start() already called in public/index.php
require_once __DIR__ . '/../../../models/ChatBox.php';
require_once __DIR__ . '/../../../models/Message.php';

$userID = $_SESSION['user_id'] ?? 1;
$selectedChatID = $_GET['id'] ?? null;

if (!$selectedChatID) {
    echo "<p>Chưa chọn cuộc trò chuyện.</p>";
    exit;
}

$chatBox = new ChatBox(0,0);
$chatList = $chatBox->getChatList($userID);

// Xác định đối tác chat
$chatPartner = null;
foreach ($chatList as $chat) {
    if ($chat['ChatBoxID'] == $selectedChatID) {
        $chatPartner = [
            'AccountID' => $chat['PartnerID'],
            'Username' => $chat['Username'] ?? 'Người dùng',
            'AvatarURL' => $chat['AvatarURL'] ?? 'https://i.pravatar.cc/40'
        ];
        break;
    }
}

if (!$chatPartner) {
    echo "<p>Không tìm thấy đối tác chat.</p>";
    exit;
}

// Lấy tin nhắn
$messageModel = new Message($selectedChatID, $userID);
$messages = $messageModel->getMessagesByChat();
?>

<div class="container py-4" style="max-width:700px;">
    <!-- Header với avatar và tên -->
    <div class="d-flex align-items-center mb-3 p-2 border bg-light rounded">
        <img src="<?= $chatPartner['AvatarURL'] ?>" class="rounded-circle me-2" width="50" height="50">
        <strong><?= $chatPartner['Username'] ?></strong>
    </div>

    <!-- Khung chat và thanh nhập -->
    <div class="card shadow-sm" style="display:flex; flex-direction:column; height:500px;">
        <!-- Khung tin nhắn scrollable -->
        <div class="card-body flex-grow-1" id="chat-box" style="overflow-y:auto; padding:10px; background-color:#f8f9fa;">
            <?php foreach($messages as $msg): ?>
                <?php include __DIR__ . '/../../components/item/message-item.php'; ?>
            <?php endforeach; ?>
        </div>

        <!-- Thanh nhập tin nhắn cố định dưới cùng -->
        <div class="card-footer p-2" style="border-top:1px solid #ddd; background-color:white;">
            <div class="input-group">
                <input type="text" id="message-input" class="form-control" placeholder="Nhập tin nhắn...">
                <button class="btn btn-primary" id="send-btn">Gửi</button>
            </div>
        </div>
    </div>
</div>

<script>
const chatBoxID = <?= $selectedChatID ?>;
const userID = <?= $userID ?>;
</script>
<script src="/assets/js/messages.js"></script>

<?php
$content = ob_get_clean();
require_once __DIR__ .'/../../layouts/main.php';
?>

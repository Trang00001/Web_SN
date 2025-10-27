<?php
session_start();
require_once __DIR__ . '/../models/ChatBox.php';
require_once __DIR__ . '/../models/Message.php';

// User đã đăng nhập
$userID = $_SESSION['userID'] ?? 1;

// 1️⃣ Lấy danh sách chat
$chatBox = new ChatBox(0,0);
$chatList = $chatBox->getChatList($userID);

// 2️⃣ Xử lý AJAX actions
$action = $_GET['action'] ?? $_POST['action'] ?? null;

// ===================== FETCH MESSAGES =====================
if ($action === 'fetch' && isset($_GET['chatBoxID'])) {
    $chatID = $_GET['chatBoxID'];
    $messageModel = new Message($chatID, $userID);
    $messages = $messageModel->getMessagesByChat();

    ob_start();
    foreach ($messages as $msg) {
        $msgUserID = $userID;
        include __DIR__ . '/../views/pages/messages/messageitem.php';
    }
    $html = ob_get_clean();

    echo json_encode([
        'success' => true,
        'html' => $html
    ]);
    exit;
}


// ===================== SEND MESSAGE =====================
// ===================== SEND MESSAGE =====================
if ($action === 'send' && isset($_POST['chatID'], $_POST['content'])) {
    $chatID = $_POST['chatID'];
    $content = trim($_POST['content']);

    if (!empty($content)) {
        $newMsg = new Message($chatID, $userID, $content);
        $sendResult = $newMsg->send(); // Lưu vào DB

        if ($sendResult) {
            // Lấy thông tin message vừa gửi để trả về
            $msgData = [
                'MessageID' => $newMsg->getMessageID(),
                'ChatBoxID' => $chatID,
                'SenderID' => $userID,
                'Content' => $content,
                'SentTime' => date('H:i') // Hoặc lấy từ DB nếu muốn chuẩn
            ];

            echo json_encode([
                'success' => true,
                'message' => $msgData
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Gửi thất bại']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Tin nhắn trống']);
    }
    exit;
}



// ===================== NORMAL PAGE LOAD =====================
$selectedChatID = $_GET['id'] ?? null;
$messages = [];
$chatPartner = null;

if ($selectedChatID) {
    $message = new Message($selectedChatID, $userID);
    $messages = $message->getMessagesByChat();

    // Xác định đối tác chat
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
}

// ===================== INCLUDE VIEW =====================
require_once __DIR__ . '/../views/pages/messages/chat.php';

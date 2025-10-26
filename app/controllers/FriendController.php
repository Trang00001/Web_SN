<?php
session_start();
require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../models/FriendRequest.php';
require_once __DIR__ . '/../models/Friendship.php';

$currentUserID = 1; // giả định user hiện tại
$action = $_GET['action'] ?? 'all';

// -------------------- Lấy request --------------------
if ($action === 'requests') {
    $fr = new FriendRequest();
    $requests = $fr->getIncomingRequests($currentUserID);

    ob_start();
    include __DIR__ . '/../views/pages/friends/requests.php';
    echo ob_get_clean();
    exit;
}

// -------------------- Lấy friend list --------------------
if ($action === 'all') {
    $fs = new Friendship();
    $friends = $fs->getFriendList($currentUserID);

    ob_start();
    foreach ($friends as $f) {
        $friendData = [
            'AccountID' => $f['AccountID'],
            'Username'  => $f['Username'],
            'AvatarURL' => $f['AvatarURL'] ?? null
        ];
        include __DIR__ . '/../views/components/item/friend-item.php';
    }
    echo ob_get_clean();
    exit;
}

// -------------------- Accept Friend Request --------------------
if ($action === 'accept' && !empty($_POST['id'])) {
    header('Content-Type: application/json; charset=utf-8');

    $requestID = (int)$_POST['id'];
    $fr = new FriendRequest($requestID);
    $friendID = $fr->accept($currentUserID);

    if ($friendID) {
    $fs = new Friendship();
    $friends = $fs->getFriendList($currentUserID);

    $newFriend = null;
    foreach ($friends as $f) {
        if ($f['AccountID'] == $friendID) {
            $newFriend = [
                'AccountID' => $f['AccountID'],
                'Username'  => $f['Username'],
                'AvatarURL' => $f['AvatarURL'] ?? null
            ];
            break;
        }
    }

    echo json_encode(['success' => true, 'newFriend' => $newFriend]);
} else {
    // Chỉ trả về success true để JS biết là accept thành công
    echo json_encode(['success' => true, 'newFriend' => null]);
}

    exit;
}



// -------------------- Reject Friend Request --------------------
if ($action === 'reject' && !empty($_POST['id'])) {
       header('Content-Type: application/json; charset=utf-8');
    $requestID = (int)$_POST['id'];
    $fr = new FriendRequest($requestID);
    $fr->reject();
    echo json_encode(['success' => true]);
    exit;
}

// -------------------- Remove Friend --------------------
if ($action === 'remove' && !empty($_POST['id'])) {
       header('Content-Type: application/json; charset=utf-8');
    $friendID = (int)$_POST['id'];
    $fs = new Friendship($currentUserID, $friendID);
    $fs->removeFriend();
    echo json_encode(['success' => true]);
    exit;
}

// -------------------- Send Friend Request --------------------
if ($action === 'send_request' && !empty($_POST['id'])) {
       header('Content-Type: application/json; charset=utf-8');
    $receiverID = (int)$_POST['id'];
    $fr = new FriendRequest();
    $fr->sendRequest($currentUserID, $receiverID);
    echo json_encode(['success' => true]);
    exit;	
}
?>
<?php
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../../core/Helpers.php';

class FriendController {
    
    private function getCurrentUserId() {
        return $_SESSION['user_id'] ?? 0;
    }
    
    private function requireAuth() {
        $userId = $this->getCurrentUserId();
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        return $userId;
    }
    
    // Get all friends
    public function getAllFriends() {
        require_once __DIR__ . '/../models/Friendship.php';
        
        $currentUserID = $this->requireAuth();
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
    }
    
    // Get friend requests
    public function getFriendRequests() {
        require_once __DIR__ . '/../models/FriendRequest.php';
        
        $currentUserID = $this->requireAuth();
        $fr = new FriendRequest();
        $requests = $fr->getIncomingRequests($currentUserID);

        ob_start();
        include __DIR__ . '/../views/pages/friends/requests.php';
        echo ob_get_clean();
    }
    
    // Accept friend request
    public function acceptRequest() {
        require_once __DIR__ . '/../models/FriendRequest.php';
        require_once __DIR__ . '/../models/Friendship.php';
        
        header('Content-Type: application/json; charset=utf-8');
        
        $currentUserID = $this->requireAuth();
        $requestID = (int)($_POST['id'] ?? 0);
        
        if (!$requestID) {
            echo json_encode(['success' => false, 'message' => 'Invalid request ID']);
            return;
        }
        
        // Lấy thông tin sender trước khi accept (vì request sẽ bị xóa sau khi accept)
        $fr = new FriendRequest($requestID);
        $incomingRequests = $fr->getIncomingRequests($currentUserID);
        $senderID = null;
        $senderName = null;
        
        foreach ($incomingRequests as $req) {
            if ($req['RequestID'] == $requestID) {
                $senderID = $req['SenderID'] ?? null;
                $senderName = $req['SenderName'] ?? null;
                break;
            }
        }
        
        // Accept request
        $fr->accept($currentUserID);
        
        // Tạo notification cho người đã gửi request (sender)
        if ($senderID && $senderID != $currentUserID) {
            try {
                $accepterName = getUsername($currentUserID);
                $notificationContent = "$accepterName đã chấp nhận lời mời kết bạn của bạn";
                
                $notification = new Notification($senderID, 'friend_request_accepted', $notificationContent);
                $createResult = $notification->create();
                
                if ($createResult) {
                    error_log("DEBUG acceptRequest - Notification tạo thành công cho senderID: $senderID");
                } else {
                    error_log("DEBUG acceptRequest - Notification không lưu được (create() trả về false)");
                }
            } catch (Exception $e) {
                error_log("DEBUG acceptRequest - Exception khi tạo notification: " . $e->getMessage());
                // Không throw exception, chỉ log vì accept đã thành công
            }
        }
        
        // Lấy thông tin friend từ friend list (vì accept() có thể trả về null sau khi request bị xóa)
        $fs = new Friendship();
        $friends = $fs->getFriendList($currentUserID);

        $newFriend = null;
        if ($senderID) {
            foreach ($friends as $f) {
                if ($f['AccountID'] == $senderID) {
                    $newFriend = [
                        'AccountID' => $f['AccountID'],
                        'Username'  => $f['Username'],
                        'AvatarURL' => $f['AvatarURL'] ?? null
                    ];
                    break;
                }
            }
        }

        echo json_encode(['success' => true, 'newFriend' => $newFriend]);
    }

// Gợi ý kết bạn
// Get suggested friends
public function getSuggestedFriends() {
    require_once __DIR__ . '/../models/Friendship.php';
    
    $currentUserID = $this->requireAuth();
    $fs = new Friendship();
    $suggested = $fs->getSuggestedFriends($currentUserID);

    ob_start();
    foreach ($suggested as $f) {
        $friendData = [
            'AccountID' => $f['AccountID'],
            'Username'  => $f['Username'],
            'AvatarURL' => $f['AvatarURL'] ?? null
        ];
        $tab = 'suggested';
        include __DIR__ . '/../views/components/item/friend-item.php';
    }
    echo ob_get_clean();
}




    
    // Reject friend request
    public function rejectRequest() {
        require_once __DIR__ . '/../models/FriendRequest.php';
        
        header('Content-Type: application/json; charset=utf-8');
        
        $currentUserID = $this->requireAuth();
        $requestID = (int)($_POST['id'] ?? 0);
        
        if (!$requestID) {
            echo json_encode(['success' => false, 'message' => 'Invalid request ID']);
            return;
        }
        
        $fr = new FriendRequest($requestID);
        $fr->reject();
        echo json_encode(['success' => true]);
    }
    
    // Remove friend
    public function removeFriend() {
        require_once __DIR__ . '/../models/Friendship.php';
        
        header('Content-Type: application/json; charset=utf-8');
        
        $currentUserID = $this->requireAuth();
        $friendID = (int)($_POST['id'] ?? 0);
        
        if (!$friendID) {
            echo json_encode(['success' => false, 'message' => 'Invalid friend ID']);
            return;
        }
        
        $fs = new Friendship($currentUserID, $friendID);
        $fs->removeFriend();
        echo json_encode(['success' => true]);
    }
    
    // Send friend request
    public function sendRequest() {
        require_once __DIR__ . '/../models/FriendRequest.php';
        
        header('Content-Type: application/json; charset=utf-8');
        
        $currentUserID = $this->requireAuth();
        $receiverID = (int)($_POST['id'] ?? 0);
        
        if (!$receiverID) {
            echo json_encode(['success' => false, 'message' => 'Invalid receiver ID']);
            return;
        }
        
        // Gửi friend request
        $fr = new FriendRequest();
        $result = $fr->sendRequest($currentUserID, $receiverID);
        
        if ($result) {
            // Tạo notification cho người nhận request (receiver)
            if ($receiverID != $currentUserID) {
                try {
                    $senderName = getUsername($currentUserID);
                    $notificationContent = "$senderName đã gửi lời mời kết bạn";
                    
                    $notification = new Notification($receiverID, 'friend_request', $notificationContent);
                    $createResult = $notification->create();
                    
                    if ($createResult) {
                        error_log("DEBUG sendRequest - Notification tạo thành công cho receiverID: $receiverID");
                    } else {
                        error_log("DEBUG sendRequest - Notification không lưu được (create() trả về false)");
                    }
                } catch (Exception $e) {
                    error_log("DEBUG sendRequest - Exception khi tạo notification: " . $e->getMessage());
                    // Không throw exception, chỉ log vì request đã được gửi thành công
                }
            }
        }
        
        echo json_encode(['success' => $result]);
    }
}
?>
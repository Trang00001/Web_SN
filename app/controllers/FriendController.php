<?php
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
            echo json_encode(['success' => true, 'newFriend' => null]);
        }
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
        
        $fr = new FriendRequest();
        $fr->sendRequest($currentUserID, $receiverID);
        echo json_encode(['success' => true]);
    }
}
?>
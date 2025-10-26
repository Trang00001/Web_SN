<?php
require_once __DIR__ . '/../../core/Database.php';

class FriendRequest {
    private $requestID;
    private $db;

    public function __construct($requestID = 0) {
        $this->requestID = $requestID;
        $this->db = new Database();
    }

    // Lấy tất cả lời mời đến (pending) cho user hiện tại
    public function getIncomingRequests($receiverID) {
        return $this->db->callProcedureSelect("sp_GetFriendRequests", [$receiverID]);
    }

    // Gửi lời mời kết bạn
    public function sendRequest($senderID, $receiverID) {
        return $this->db->callProcedureExecute("sp_SendFriendRequest", [$senderID, $receiverID]);
    }

    // Hủy lời mời đã gửi
    public function cancelRequest($senderID, $receiverID) {
        return $this->db->callProcedureExecute("sp_CancelFriendRequest", [$senderID, $receiverID]);
    }

    // Chấp nhận lời mời
    public function accept($currentUserID) {
        // Gọi procedure chấp nhận lời mời, sẽ cập nhật FriendRequest & Friendship
        $this->db->callProcedureExecute("sp_AcceptFriendRequest", [$this->requestID]);

        // Lấy thông tin sender của request vừa accept để trả về
        $requests = $this->getIncomingRequests($currentUserID);
        foreach ($requests as $r) {
            if ($r['RequestID'] == $this->requestID) {
                return [
                    'AccountID' => $r['SenderID'],
                    'Username'  => $r['SenderName'],
                    'AvatarURL' => $r['AvatarURL'] ?? null
                ];
            }
        }
        return null;
    }

    // Từ chối lời mời
    public function reject() {
        return $this->db->callProcedureExecute("sp_RejectFriendRequest", [$this->requestID]);
    }
}
?>
<?php
require_once __DIR__ . '/../../core/Database.php';

class FriendRequest {
    private $requestID;
    private $senderID;
    private $receiverID;
    private $status; // Pending, Accepted, Rejected
    private $db;

    public function __construct($senderID, $receiverID, $status = "Pending", $requestID = null) {
        $this->senderID = $senderID;
        $this->receiverID = $receiverID;
        $this->status = $status;
        $this->requestID = $requestID;
        $this->db = new Database();
    }

    // Getter & Setter
    public function getRequestID() { return $this->requestID; }
    public function getSenderID() { return $this->senderID; }
    public function getReceiverID() { return $this->receiverID; }
    public function getStatus() { return $this->status; }

    public function setRequestID($requestID) { $this->requestID = $requestID; }
    public function setSenderID($senderID) { $this->senderID = $senderID; }
    public function setReceiverID($receiverID) { $this->receiverID = $receiverID; }
    public function setStatus($status) { $this->status = $status; }

    // Functions
    public function send() {
        return $this->db->callProcedureExecute("sp_SendFriendRequest", [$this->senderID, $this->receiverID]);
    }

    public function cancel() {
        return $this->db->callProcedureExecute("sp_CancelFriendRequest", [$this->senderID, $this->receiverID]);
    }

    public function accept() {
        return $this->db->callProcedureExecute("sp_AcceptFriendRequest", [$this->requestID]);
    }

    public function reject() {
        return $this->db->callProcedureExecute("sp_RejectFriendRequest", [$this->requestID]);
    }

    public function getIncomingRequests($accountID) {
        return $this->db->callProcedureSelect("sp_GetFriendRequests", [$accountID]);
    }
}
?>

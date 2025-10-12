<?php
require_once __DIR__ . '/../../core/Database.php';

class Notification {
    private $notificationID;
    private $receiverID;
    private $type;
    private $content;
    private $isRead;
    private $db;

    public function __construct($receiverID, $type = "", $content = "", $isRead = false, $notificationID = null) {
        $this->receiverID = $receiverID;
        $this->type = $type;
        $this->content = $content;
        $this->isRead = $isRead;
        $this->notificationID = $notificationID;
        $this->db = new Database();
    }

    // Getter & Setter
    public function getNotificationID() { return $this->notificationID; }
    public function getReceiverID() { return $this->receiverID; }
    public function getType() { return $this->type; }
    public function getContent() { return $this->content; }
    public function getIsRead() { return $this->isRead; }

    public function setNotificationID($notificationID) { $this->notificationID = $notificationID; }
    public function setReceiverID($receiverID) { $this->receiverID = $receiverID; }
    public function setType($type) { $this->type = $type; }
    public function setContent($content) { $this->content = $content; }
    public function setIsRead($isRead) { $this->isRead = $isRead; }

    // Functions
    public function create() {
        return $this->db->callProcedureExecute("sp_CreateNotification", [$this->receiverID, $this->type, $this->content]);
    }

    public function getAll() {
        return $this->db->callProcedureSelect("sp_GetNotifications", [$this->receiverID]);
    }

    public function markAsRead() {
        return $this->db->callProcedureExecute("sp_MarkNotificationAsRead", [$this->notificationID]);
    }

    public function delete() {
        return $this->db->callProcedureExecute("sp_DeleteNotification", [$this->notificationID]);
    }
}
?>

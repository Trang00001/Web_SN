<?php
require_once __DIR__ . '/../../core/Database.php';

class Message {
    private $messageID;
    private $chatBoxID;
    private $senderID;
    private $content;
    private $db;

    public function __construct($chatBoxID, $senderID, $content = "", $messageID = null) {
        $this->chatBoxID = $chatBoxID;
        $this->senderID = $senderID;
        $this->content = $content;
        $this->messageID = $messageID;
        $this->db = new Database();
    }

    // Getter & Setter
    public function getMessageID() { return $this->messageID; }
    public function getChatBoxID() { return $this->chatBoxID; }
    public function getSenderID() { return $this->senderID; }
    public function getContent() { return $this->content; }

    public function setMessageID($messageID) { $this->messageID = $messageID; }
    public function setChatBoxID($chatBoxID) { $this->chatBoxID = $chatBoxID; }
    public function setSenderID($senderID) { $this->senderID = $senderID; }
    public function setContent($content) { $this->content = $content; }

    // Functions
    public function send() {
        return $this->db->callProcedureExecute("sp_SendMessage", [$this->chatBoxID, $this->senderID, $this->content]);
    }

    public function delete() {
        return $this->db->callProcedureExecute("sp_DeleteMessage", [$this->messageID]);
    }

    public function getMessagesByChat() {
        return $this->db->callProcedureSelect("sp_GetMessagesByChatId", [$this->chatBoxID]);
    }
}
?>

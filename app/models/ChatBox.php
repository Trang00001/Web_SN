<?php
require_once __DIR__ . '/../../core/Database.php';

class ChatBox {
    private $chatBoxID;
    private $account1ID;
    private $account2ID;
    private $status;
    private $db;

    public function __construct($account1ID, $account2ID, $status = "active", $chatBoxID = null) {
        $this->account1ID = $account1ID;
        $this->account2ID = $account2ID;
        $this->status = $status;
        $this->chatBoxID = $chatBoxID;
        $this->db = new Database();
    }

    // Getter & Setter
    public function getChatBoxID() { return $this->chatBoxID; }
    public function getAccount1ID() { return $this->account1ID; }
    public function getAccount2ID() { return $this->account2ID; }
    public function getStatus() { return $this->status; }

    public function setChatBoxID($chatBoxID) { $this->chatBoxID = $chatBoxID; }
    public function setAccount1ID($account1ID) { $this->account1ID = $account1ID; }
    public function setAccount2ID($account2ID) { $this->account2ID = $account2ID; }
    public function setStatus($status) { $this->status = $status; }

    // Functions
    public function create() {
        return $this->db->callProcedureExecute("sp_CreateChatBox", [$this->account1ID, $this->account2ID]);
    }

    public function getChatList($accountID) {
        return $this->db->callProcedureSelect("sp_GetChatList", [$accountID]);
    }
}
?>

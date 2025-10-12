<?php
require_once __DIR__ . '/../../core/Database.php';

class SavedPost {
    private $accountID;
    private $postID;
    private $type; // Saved / Hidden
    private $db;

    public function __construct($accountID, $postID, $type = "Saved") {
        $this->accountID = $accountID;
        $this->postID = $postID;
        $this->type = $type;
        $this->db = new Database();
    }

    // Getter & Setter
    public function getAccountID() { return $this->accountID; }
    public function getPostID() { return $this->postID; }
    public function getType() { return $this->type; }

    public function setAccountID($accountID) { $this->accountID = $accountID; }
    public function setPostID($postID) { $this->postID = $postID; }
    public function setType($type) { $this->type = $type; }

    // Functions
    public function savePost() {
        return $this->db->callProcedureExecute("sp_SavePost", [$this->accountID, $this->postID]);
    }

    public function hidePost() {
        return $this->db->callProcedureExecute("sp_HidePost", [$this->accountID, $this->postID]);
    }
}
?>

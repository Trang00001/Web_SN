<?php
require_once __DIR__ . '/../../core/Database.php';

class PostLike {
    private $accountID;
    private $postID;
    private $likeType;
    private $db;

    public function __construct($accountID, $postID, $likeType = "Like") {
        $this->accountID = $accountID;
        $this->postID = $postID;
        $this->likeType = $likeType;
        $this->db = new Database();
    }

    // Getter & Setter
    public function getAccountID() { return $this->accountID; }
    public function getPostID() { return $this->postID; }
    public function getLikeType() { return $this->likeType; }

    public function setAccountID($accountID) { $this->accountID = $accountID; }
    public function setPostID($postID) { $this->postID = $postID; }
    public function setLikeType($likeType) { $this->likeType = $likeType; }

    // Functions
    public function like() {
        return $this->db->callProcedureExecute("sp_AddLike", [$this->accountID, $this->postID]);
    }

    public function unlike() {
        return $this->db->callProcedureExecute("sp_RemoveLike", [$this->accountID, $this->postID]);
    }
}
?>

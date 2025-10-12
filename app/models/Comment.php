<?php
require_once __DIR__ . '/../../core/Database.php';

class Comment {
    private $commentID;
    private $postID;
    private $accountID;
    private $content;
    private $db;

    public function __construct($postID, $accountID, $content = "", $commentID = null) {
        $this->postID = $postID;
        $this->accountID = $accountID;
        $this->content = $content;
        $this->commentID = $commentID;
        $this->db = new Database();
    }

    // Getter & Setter
    public function getCommentID() { return $this->commentID; }
    public function getPostID() { return $this->postID; }
    public function getAccountID() { return $this->accountID; }
    public function getContent() { return $this->content; }

    public function setCommentID($commentID) { $this->commentID = $commentID; }
    public function setPostID($postID) { $this->postID = $postID; }
    public function setAccountID($accountID) { $this->accountID = $accountID; }
    public function setContent($content) { $this->content = $content; }

    // Functions
    public function add() {
        return $this->db->callProcedureExecute("sp_AddComment", [$this->postID, $this->accountID, $this->content]);
    }

    public function getByPost() {
        return $this->db->callProcedureSelect("sp_GetCommentsByPostId", [$this->postID]);
    }
}
?>

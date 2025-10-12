<?php
require_once __DIR__ . '/../../core/Database.php';

class SavedPost {
    private $accountID;
    private $postID;
    private $categoryID;
    private $type; // Saved / Hidden
    private $db;

    public function __construct($accountID, $postID, $categoryID, $type = "Saved") {
        $this->accountID = $accountID;
        $this->postID = $postID;
        $this->categoryID = $categoryID;
        $this->type = $type;
        $this->db = new Database();
    }

    // GETTER & SETTER
    public function getAccountID() { return $this->accountID; }
    public function getPostID() { return $this->postID; }
    public function getCategoryID() { return $this->categoryID; }
    public function getType() { return $this->type; }

    public function setAccountID($accountID) { $this->accountID = $accountID; }
    public function setPostID($postID) { $this->postID = $postID; }
    public function setCategoryID($categoryID) { $this->categoryID = $categoryID; }
    public function setType($type) { $this->type = $type; }

    // FUNCTION: Save post
    public function savePost() {
        return $this->db->callProcedureExecute("sp_SavePost", [
            $this->accountID, $this->postID, $this->categoryID
        ]);
    }

    // FUNCTION: Hide post
    public function hidePost() {
        return $this->db->callProcedureExecute("sp_HidePost", [
            $this->accountID, $this->postID, $this->categoryID
        ]);
    }
}
?>

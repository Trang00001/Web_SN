<?php
require_once __DIR__ . '/../../core/Database.php';

class Post {
    private $postID;
    private $authorID;
    private $content;
    private $categoryID;
    private $db;

    public function __construct($authorID, $content = "", $postID = null, $categoryID = 1) {
        $this->authorID = $authorID;
        $this->content = $content;
        $this->postID = $postID;
        $this->categoryID = $categoryID;
        $this->db = new Database();
    }

    // Getter & Setter
    public function getPostID() { return $this->postID; }
    public function getAuthorID() { return $this->authorID; }
    public function getContent() { return $this->content; }
    public function getCategoryID() { return $this->categoryID; }

    public function setPostID($postID) { $this->postID = $postID; }
    public function setAuthorID($authorID) { $this->authorID = $authorID; }
    public function setContent($content) { $this->content = $content; }
    public function setCategoryID($categoryID) { $this->categoryID = $categoryID; }

    // Functions
    public function create() {
        $result = $this->db->callProcedureWithOutParam("sp_CreatePost", [$this->authorID, $this->content, $this->categoryID]);
        if ($result && isset($result['out_param'])) {
            $this->postID = $result['out_param'];
            return true;
        }
        return false;
    }

    public function update() {
        return $this->db->callProcedureExecute("sp_UpdatePost", [$this->postID, $this->content]);
    }

    public function delete() {
        return $this->db->callProcedureExecute("sp_DeletePost", [$this->postID]);
    }

    public function getAll() {
        return $this->db->callProcedureSelect("sp_GetAllPosts");
    }

    public function getById() {
        return $this->db->callProcedureSelect("sp_GetPostById", [$this->postID]);
    }

    public function getByUser() {
        return $this->db->callProcedureSelect("sp_GetUserPosts", [$this->authorID]);
    }
}
?>

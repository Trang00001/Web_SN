<?php
require_once __DIR__ . '/../../core/Database.php';

class PostCategory {
    private $categoryID;
    private $categoryName;
    private $db;

    public function __construct($categoryName = "", $categoryID = null) {
        $this->categoryName = $categoryName;
        $this->categoryID = $categoryID;
        $this->db = new Database();
    }

    // Getter & Setter
    public function getCategoryID() { return $this->categoryID; }
    public function getCategoryName() { return $this->categoryName; }
    public function setCategoryID($categoryID) { $this->categoryID = $categoryID; }
    public function setCategoryName($categoryName) { $this->categoryName = $categoryName; }

    // Functions
    public function create() {
        $query = "INSERT INTO PostCategory (CategoryName) VALUES (?)";
        return $this->db->execute($query, [$this->categoryName]);
    }

    public function getAll() {
        $query = "SELECT CategoryID, CategoryName FROM PostCategory ORDER BY CategoryID";
        return $this->db->select($query);
    }
}
?>

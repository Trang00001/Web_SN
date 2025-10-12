<?php
require_once __DIR__ . '/../../core/Database.php';

class Image {
    private $imageID;
    private $postID;
    private $imageURL;
    private $db;

    public function __construct($postID, $imageURL, $imageID = null) {
        $this->postID = $postID;
        $this->imageURL = $imageURL;
        $this->imageID = $imageID;
        $this->db = new Database();
    }

    // Getter & Setter
    public function getImageID() { return $this->imageID; }
    public function getPostID() { return $this->postID; }
    public function getImageURL() { return $this->imageURL; }
    public function setImageID($imageID) { $this->imageID = $imageID; }
    public function setPostID($postID) { $this->postID = $postID; }
    public function setImageURL($imageURL) { $this->imageURL = $imageURL; }

    // Functions
    public function addImage() {
        $query = "INSERT INTO Image (PostID, ImageURL) VALUES (?, ?)";
        return $this->db->executeQuery($query, [$this->postID, $this->imageURL]);
    }

    public function getImagesByPost() {
        $query = "SELECT * FROM Image WHERE PostID = ?";
        return $this->db->executeQuery($query, [$this->postID]);
    }
}
?>

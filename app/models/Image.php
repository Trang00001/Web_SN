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
        return $this->db->callProcedureExecute("sp_AddPostImage", [$this->postID, $this->imageURL]);
    }

    public function getImagesByPost() {
        return $this->db->callProcedureSelect("sp_GetImagesByPost", [$this->postID]);
    }

    /**
     * Get all images for a specific post
     * @param int $postId - The post ID
     * @return array - Array of images with absolute URLs
     */
    public function getByPostId($postId) {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("
                SELECT ImageID, PostID, ImageURL 
                FROM Image 
                WHERE PostID = ? 
                ORDER BY ImageID ASC
            ");
            $stmt->bind_param("i", $postId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $images = [];
            while ($row = $result->fetch_assoc()) {
                $imageUrl = $row['ImageURL'];
                
                // Convert to absolute URL if needed
                if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
                    // Remove leading slash if exists
                    $imageUrl = ltrim($imageUrl, '/');
                    // Add full URL
                    if (str_starts_with($imageUrl, 'WEB-SN/')) {
                        $imageUrl = 'http://localhost/' . $imageUrl;
                    } else {
                        $imageUrl = 'http://localhost/WEB-SN/' . $imageUrl;
                    }
                }
                
                $images[] = [
                    'id' => $row['ImageID'],
                    'post_id' => $row['PostID'],
                    'url' => $imageUrl
                ];
            }
            
            $stmt->close();
            return $images;
        } catch (Exception $e) {
            error_log("Error getting images for post $postId: " . $e->getMessage());
            return [];
        }
    }
}
?>

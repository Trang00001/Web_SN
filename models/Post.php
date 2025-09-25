<?php
// Post.php
class Post {
    public int $postId;
    public string $content;
    public int $categoryId;
    public DateTime $createdAt;

    public function __construct($postId,$categoryId, $content) {
        $this->postId = $postId;
        $this->content = $content;
        $this->categoryId = $categoryId;
        $this->createdAt = new DateTime();
    }

    public function createPost() {}
    public function editPost() {}
    public function deletePost() {}
}

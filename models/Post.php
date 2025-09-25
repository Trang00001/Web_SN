<?php
// Post.php
class Post {
    public int $postId;
    public string $content;
    public DateTime $createdAt;

    public function __construct($postId, $content) {
        $this->postId = $postId;
        $this->content = $content;
        $this->createdAt = new DateTime();
    }

    public function createPost() {}
    public function editPost() {}
    public function deletePost() {}
}

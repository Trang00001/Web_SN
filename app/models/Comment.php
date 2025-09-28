<?php
// Comment.php
class Comment {
    public int $commentId;
    public string $content;
    public DateTime $createdAt;

    public function __construct($commentId, $content) {
        $this->commentId = $commentId;
        $this->content = $content;
        $this->createdAt = new DateTime();
    }

    public function sendMessage() {}
    public function readMessage() {}
}

<?php
// Like.php
class Like {
    public int $likeId;
    public DateTime $createdAt;

    public function __construct($likeId) {
        $this->likeId = $likeId;
        $this->createdAt = new DateTime();
    }

    public function toggleLike() {}
}

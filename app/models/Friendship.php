<?php
// Friendship.php
class Friendship {
    public int $id;
    public string $status; // pending, accepted, rejected
    public DateTime $createdAt;

    public function __construct($id, $status) {
        $this->id = $id;
        $this->status = $status;
        $this->createdAt = new DateTime();
    }

    public function sendMessage() {}
    public function readMessage() {}
}

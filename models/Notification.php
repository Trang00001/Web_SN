<?php
// Notification.php
class Notification {
    public int $notificationId;
    public string $type; // Like, Comment, Share
    public bool $isRead;
    public DateTime $createdAt;

    public function __construct($notificationId, $type) {
        $this->notificationId = $notificationId;
        $this->type = $type;
        $this->isRead = false;
        $this->createdAt = new DateTime();
    }

    public function markAsRead() {
        $this->isRead = true;
    }
}

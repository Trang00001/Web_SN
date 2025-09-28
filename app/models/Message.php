<?php
// Message.php
class Message {
    public int $messageId;
    public string $content;
    public DateTime $createdAt;

    public function __construct($messageId, $content) {
        $this->messageId = $messageId;
        $this->content = $content;
        $this->createdAt = new DateTime();
    }

    public function sendMessage() {}
    public function readMessage() {}
}

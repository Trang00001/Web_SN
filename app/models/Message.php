<?php
require_once 'BaseModel.php';

class Message extends BaseModel {
    private ?int $message_id;
    private int $sender_id;
    private int $receiver_id;
    private string $content;
    private ?string $created_at;

    public function __construct(
        int $sender_id = 0,
        int $receiver_id = 0,
        string $content = '',
        ?string $created_at = null,
        ?int $message_id = null
    ) {
        parent::__construct();
        $this->sender_id  = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->content    = $content;
        $this->created_at = $created_at;
        $this->message_id = $message_id;
    }

    // ===== Getter / Setter =====
    public function getId(): ?int { return $this->message_id; }
    public function getSenderId(): int { return $this->sender_id; }
    public function setSenderId(int $v): void { $this->sender_id = $v; }
    public function getReceiverId(): int { return $this->receiver_id; }
    public function setReceiverId(int $v): void { $this->receiver_id = $v; }
    public function getContent(): string { return $this->content; }
    public function setContent(string $v): void { $this->content = $v; }
    public function getCreatedAt(): ?string { return $this->created_at; }

    // ===== Nghiệp vụ =====
    public function send(): bool {
        $s = $this->sender_id;
        $r = $this->receiver_id;
        $c = mysqli_real_escape_string($this->db->conn, $this->content);
        $sql = "INSERT INTO Message(sender_id, receiver_id, content) VALUES($s, $r, '$c')";
        return $this->db->execute($sql);
    }

    public static function conversation(int $userA, int $userB): array {
        $db = new Database();
        return $db->select(
            "SELECT * FROM Message
             WHERE (sender_id=$userA AND receiver_id=$userB)
                OR (sender_id=$userB AND receiver_id=$userA)
             ORDER BY created_at ASC"
        );
    }
}

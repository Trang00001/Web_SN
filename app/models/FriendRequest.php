<?php
require_once 'BaseModel.php';

class FriendRequest extends BaseModel {
    private ?int $request_id;
    private int $sender_id;
    private int $receiver_id;
    private string $status; // 'pending', 'accepted', 'rejected'

    public function __construct(
        int $sender_id = 0,
        int $receiver_id = 0,
        string $status = 'pending',
        ?int $request_id = null
    ) {
        parent::__construct();
        $this->sender_id   = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->status      = $status;
        $this->request_id  = $request_id;
    }

    // ===== Getter / Setter =====
    public function getId(): ?int { return $this->request_id; }
    public function getSenderId(): int { return $this->sender_id; }
    public function setSenderId(int $v): void { $this->sender_id = $v; }
    public function getReceiverId(): int { return $this->receiver_id; }
    public function setReceiverId(int $v): void { $this->receiver_id = $v; }
    public function getStatus(): string { return $this->status; }
    public function setStatus(string $v): void {
        if (!in_array($v, ['pending', 'accepted', 'rejected'], true)) {
            throw new InvalidArgumentException("Invalid status");
        }
        $this->status = $v;
    }

    // ===== Nghiệp vụ =====
    public function send(): bool {
        $s = $this->sender_id;
        $r = $this->receiver_id;
        $sql = "INSERT INTO FriendRequest(sender_id,receiver_id,status)
                VALUES($s,$r,'pending')";
        return $this->db->execute($sql);
    }

    public function accept(): bool {
        if (!$this->request_id) return false;
        return $this->db->execute(
            "UPDATE FriendRequest SET status='accepted' WHERE request_id={$this->request_id}"
        );
    }

    public function reject(): bool {
        if (!$this->request_id) return false;
        return $this->db->execute(
            "UPDATE FriendRequest SET status='rejected' WHERE request_id={$this->request_id}"
        );
    }

    public function cancel(): bool {
        if (!$this->request_id) return false;
        return $this->db->execute(
            "DELETE FROM FriendRequest WHERE request_id={$this->request_id}"
        );
    }

    public static function pendingForUser(int $uid): array {
        $db = new Database();
        return $db->select(
            "SELECT * FROM FriendRequest WHERE receiver_id=$uid AND status='pending'"
        );
    }
}

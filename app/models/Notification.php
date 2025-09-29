<?php
require_once 'BaseModel.php';

class Notification extends BaseModel {
    private ?int $notification_id;
    private int $user_id;
    private string $type;        // 'like','comment','share','friend_request'
    private int $reference_id;
    private ?string $created_at;

    public function __construct(
        int $user_id = 0,
        string $type = '',
        int $reference_id = 0,
        ?string $created_at = null,
        ?int $notification_id = null
    ) {
        parent::__construct();
        $this->user_id        = $user_id;
        $this->type           = $type;
        $this->reference_id   = $reference_id;
        $this->created_at     = $created_at;
        $this->notification_id = $notification_id;
    }

    // ===== Getter / Setter =====
    public function getId(): ?int { return $this->notification_id; }
    public function getUserId(): int { return $this->user_id; }
    public function setUserId(int $v): void { $this->user_id = $v; }
    public function getType(): string { return $this->type; }
    public function setType(string $v): void { $this->type = $v; }
    public function getReferenceId(): int { return $this->reference_id; }
    public function setReferenceId(int $v): void { $this->reference_id = $v; }
    public function getCreatedAt(): ?string { return $this->created_at; }

    // ===== Nghiệp vụ =====
    public function save(): bool {
        $u = $this->user_id;
        $t = mysqli_real_escape_string($this->db->conn, $this->type);
        $r = $this->reference_id;
        $sql = "INSERT INTO Notification(user_id, type, reference_id)
                VALUES($u, '$t', $r)";
        return $this->db->execute($sql);
    }

    public static function getForUser(int $uid): array {
        $db = new Database();
        return $db->select(
            "SELECT * FROM Notification WHERE user_id=$uid ORDER BY created_at DESC"
        );
    }
}

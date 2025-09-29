<?php
require_once 'BaseModel.php';

class Like extends BaseModel {
    private ?int $like_id;
    private int $post_id;
    private int $user_id;
    private string $type; // 'like' hoặc 'dislike'

    public function __construct(
        int $post_id = 0,
        int $user_id = 0,
        string $type = 'like',
        ?int $like_id = null
    ) {
        parent::__construct();
        $this->post_id = $post_id;
        $this->user_id = $user_id;
        $this->type    = $type;
        $this->like_id = $like_id;
    }

    // ===== Getter / Setter =====
    public function getId(): ?int { return $this->like_id; }
    public function getPostId(): int { return $this->post_id; }
    public function setPostId(int $v): void { $this->post_id = $v; }
    public function getUserId(): int { return $this->user_id; }
    public function setUserId(int $v): void { $this->user_id = $v; }
    public function getType(): string { return $this->type; }
    public function setType(string $v): void {
        if (!in_array($v, ['like', 'dislike'], true)) {
            throw new InvalidArgumentException("Type must be 'like' or 'dislike'");
        }
        $this->type = $v;
    }

    // ===== Nghiệp vụ =====
    public function save(): bool {
        $p = $this->post_id;
        $u = $this->user_id;
        $t = mysqli_real_escape_string($this->db->conn, $this->type);
        // Nếu đã tồn tại thì cập nhật type
        $sql = "INSERT INTO `Like`(post_id,user_id,type)
                VALUES($p,$u,'$t')
                ON DUPLICATE KEY UPDATE type='$t'";
        return $this->db->execute($sql);
    }

    public function delete(): bool {
        $sql = "DELETE FROM `Like` WHERE post_id={$this->post_id} AND user_id={$this->user_id}";
        return $this->db->execute($sql);
    }

    public static function countByPost(int $postId): int {
        $db = new Database();
        $rows = $db->select("SELECT COUNT(*) AS total FROM `Like` WHERE post_id=$postId AND type='like'");
        return $rows ? (int)$rows[0]['total'] : 0;
    }
}

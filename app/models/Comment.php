<?php
require_once 'BaseModel.php';

class Comment extends BaseModel {
    private ?int $comment_id;
    private int $post_id;
    private int $user_id;
    private string $content;

    public function __construct(
        int $post_id = 0,
        int $user_id = 0,
        string $content = '',
        ?int $comment_id = null
    ) {
        parent::__construct();
        $this->post_id    = $post_id;
        $this->user_id    = $user_id;
        $this->content    = $content;
        $this->comment_id = $comment_id;
    }

    // ===== Getter / Setter =====
    public function getId(): ?int { return $this->comment_id; }
    public function getPostId(): int { return $this->post_id; }
    public function setPostId(int $v): void { $this->post_id = $v; }
    public function getUserId(): int { return $this->user_id; }
    public function setUserId(int $v): void { $this->user_id = $v; }
    public function getContent(): string { return $this->content; }
    public function setContent(string $v): void { $this->content = $v; }

    // ===== Nghiệp vụ =====
    public function save(): bool {
        $p = $this->post_id;
        $u = $this->user_id;
        $c = mysqli_real_escape_string($this->db->conn, $this->content);
        $sql = "INSERT INTO Comment(post_id,user_id,content) VALUES($p,$u,'$c')";
        return $this->db->execute($sql);
    }

    public function delete(): bool {
        if (!$this->comment_id) return false;
        return $this->db->execute("DELETE FROM Comment WHERE comment_id={$this->comment_id}");
    }

    public static function getByPost(int $postId): array {
        $db = new Database();
        return $db->select(
            "SELECT * FROM Comment WHERE post_id=$postId ORDER BY comment_id ASC"
        );
    }
}

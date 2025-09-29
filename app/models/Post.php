<?php
require_once 'BaseModel.php';

class Post extends BaseModel {
    private ?int $post_id;
    private int $user_id;
    private string $content;
    private ?string $media_url;
    private ?int $category_id;

    public function __construct(
        int $user_id = 0,
        string $content = '',
        ?string $media_url = null,
        ?int $category_id = null,
        ?int $post_id = null
    ) {
        parent::__construct();
        $this->user_id    = $user_id;
        $this->content    = $content;
        $this->media_url  = $media_url;
        $this->category_id = $category_id;
        $this->post_id    = $post_id;
    }

    // ===== Getter / Setter =====
    public function getId(): ?int { return $this->post_id; }
    public function getUserId(): int { return $this->user_id; }
    public function setUserId(int $v): void { $this->user_id = $v; }
    public function getContent(): string { return $this->content; }
    public function setContent(string $v): void { $this->content = $v; }
    public function getMediaUrl(): ?string { return $this->media_url; }
    public function setMediaUrl(?string $v): void { $this->media_url = $v; }
    public function getCategoryId(): ?int { return $this->category_id; }
    public function setCategoryId(?int $v): void { $this->category_id = $v; }

    // ===== Nghiệp vụ =====
    public function save(): bool {
        $u = $this->user_id;
        $c = mysqli_real_escape_string($this->db->conn, $this->content);
        $m = $this->media_url ? "'" . mysqli_real_escape_string($this->db->conn, $this->media_url) . "'" : "NULL";
        $cat = $this->category_id ? $this->category_id : "NULL";
        $sql = "INSERT INTO Post(user_id,content,media_url,category_id)
                VALUES($u,'$c',$m,$cat)";
        return $this->db->execute($sql);
    }

    public function update(): bool {
        if (!$this->post_id) return false;
        $c = mysqli_real_escape_string($this->db->conn, $this->content);
        $m = $this->media_url ? "'" . mysqli_real_escape_string($this->db->conn, $this->media_url) . "'" : "NULL";
        $cat = $this->category_id ? $this->category_id : "NULL";
        $sql = "UPDATE Post SET content='$c', media_url=$m, category_id=$cat
                WHERE post_id={$this->post_id}";
        return $this->db->execute($sql);
    }

    public function delete(): bool {
        if (!$this->post_id) return false;
        return $this->db->execute("DELETE FROM Post WHERE post_id={$this->post_id}");
    }

    public static function findById(int $id): ?Post {
        $db = new Database();
        $rows = $db->select("SELECT * FROM Post WHERE post_id=$id LIMIT 1");
        if (!$rows) return null;
        $r = $rows[0];
        return new Post(
            (int)$r['user_id'],
            $r['content'],
            $r['media_url'],
            $r['category_id'] !== null ? (int)$r['category_id'] : null,
            (int)$r['post_id']
        );
    }

    public static function findByUser(int $userId): array {
        $db = new Database();
        return $db->select("SELECT * FROM Post WHERE user_id=$userId ORDER BY post_id DESC");
    }
}

<?php
require_once 'BaseModel.php';

class Follow extends BaseModel {
    private int $follower_id;
    private int $following_id;

    public function __construct(int $follower_id = 0, int $following_id = 0) {
        parent::__construct();
        $this->follower_id  = $follower_id;
        $this->following_id = $following_id;
    }

    // ===== Getter / Setter =====
    public function getFollowerId(): int { return $this->follower_id; }
    public function setFollowerId(int $v): void { $this->follower_id = $v; }
    public function getFollowingId(): int { return $this->following_id; }
    public function setFollowingId(int $v): void { $this->following_id = $v; }

    // ===== Nghiệp vụ =====
    public function follow(): bool {
        $f1 = $this->follower_id;
        $f2 = $this->following_id;
        $sql = "INSERT IGNORE INTO Follow(follower_id, following_id) VALUES($f1, $f2)";
        return $this->db->execute($sql);
    }

    public function unfollow(): bool {
        $f1 = $this->follower_id;
        $f2 = $this->following_id;
        $sql = "DELETE FROM Follow WHERE follower_id=$f1 AND following_id=$f2";
        return $this->db->execute($sql);
    }

    public static function followersOf(int $uid): array {
        $db = new Database();
        return $db->select("SELECT follower_id FROM Follow WHERE following_id=$uid");
    }

    public static function followingOf(int $uid): array {
        $db = new Database();
        return $db->select("SELECT following_id FROM Follow WHERE follower_id=$uid");
    }
}

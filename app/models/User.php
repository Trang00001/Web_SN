<?php

require_once 'BaseModel.php';

class User extends BaseModel {
    private ?int $user_id;
    private string $username;
    private string $email;
    private string $password;

    public function __construct(
        string $username = '',
        string $email = '',
        string $password = '',
        ?int $user_id = null
    ) {
        parent::__construct();
        $this->username = $username;
        $this->email    = $email;
        $this->password = $password;
        $this->user_id  = $user_id;
    }

    // ===== Getter / Setter =====
    public function getId(): ?int { return $this->user_id; }
    public function getUsername(): string { return $this->username; }
    public function setUsername(string $v): void { $this->username = $v; }
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $v): void { $this->email = $v; }
    public function getPassword(): string { return $this->password; }
    public function setPassword(string $v): void { $this->password = $v; }

    // ===== Nghiệp vụ =====
    public function save(): bool {
        $u = mysqli_real_escape_string($this->db->conn, $this->username);
        $e = mysqli_real_escape_string($this->db->conn, $this->email);
        $p = password_hash($this->password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO User(username,email,password) VALUES('$u','$e','$p')";
        return $this->db->execute($sql);
    }

    public function updateProfile(): bool {
        if (!$this->user_id) return false;
        $u = mysqli_real_escape_string($this->db->conn, $this->username);
        $e = mysqli_real_escape_string($this->db->conn, $this->email);
        $sql = "UPDATE User SET username='$u', email='$e' WHERE user_id={$this->user_id}";
        return $this->db->execute($sql);
    }

    public function delete(): bool {
        if (!$this->user_id) return false;
        return $this->db->execute("DELETE FROM User WHERE user_id={$this->user_id}");
    }

    public static function findById(int $id): ?User {
        $db = new Database();
        $rows = $db->select("SELECT * FROM User WHERE user_id=$id LIMIT 1");
        if (!$rows) return null;
        $r = $rows[0];
        return new User($r['username'], $r['email'], $r['password'], (int)$r['user_id']);
    }

    public static function findByEmail(string $email): ?User {
        $db = new Database();
        $e  = mysqli_real_escape_string($db->conn, $email);
        $rows = $db->select("SELECT * FROM User WHERE email='$e' LIMIT 1");
        if (!$rows) return null;
        $r = $rows[0];
        return new User($r['username'], $r['email'], $r['password'], (int)$r['user_id']);
    }
}

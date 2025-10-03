<?php
require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel {
    public ?int $id;
    public string $username;
    public string $email;
    public string $password_hash;
    public ?string $avatar;

    public function __construct(array $data = []) {
        parent::__construct();
        $this->id = $data['id'] ?? null;
        $this->username = $data['username'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password_hash = $data['password_hash'] ?? '';
        $this->avatar = $data['avatar'] ?? null;
    }

    public function create(string $username, string $email, string $password): bool {
        $sql = "INSERT INTO users (username, email, password_hash) VALUES (:u, :e, :p)";
        $stmt = $this->pdo->prepare($sql);
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $stmt->execute([':u'=>$username, ':e'=>$email, ':p'=>$hash]);
    }

    public function findByEmail(string $email): ?User {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :e LIMIT 1");
        $stmt->execute([':e'=>$email]);
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }

    public function findById(int $id): ?User {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }
}

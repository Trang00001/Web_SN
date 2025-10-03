<?php
require_once 'BaseModel.php';

class Category extends BaseModel {
    private ?int $category_id;
    private string $name;

    public function __construct(string $name = '', ?int $category_id = null) {
        parent::__construct();
        $this->name = $name;
        $this->category_id = $category_id;
    }

    // ===== Getter / Setter =====
    public function getId(): ?int { return $this->category_id; }
    public function getName(): string { return $this->name; }
    public function setName(string $v): void { $this->name = $v; }

    // ===== Nghiệp vụ =====
    public function save(): bool {
        $n = mysqli_real_escape_string($this->db->conn, $this->name);
        $sql = "INSERT INTO Category(name) VALUES('$n')";
        return $this->db->execute($sql);
    }

    public function update(): bool {
        if (!$this->category_id) return false;
        $n = mysqli_real_escape_string($this->db->conn, $this->name);
        return $this->db->execute(
            "UPDATE Category SET name='$n' WHERE category_id={$this->category_id}"
        );
    }

    public function delete(): bool {
        if (!$this->category_id) return false;
        return $this->db->execute("DELETE FROM Category WHERE category_id={$this->category_id}");
    }

    public static function getAll(): array {
        $db = new Database();
        return $db->select("SELECT * FROM Category ORDER BY name");
    }
}

<?php
require_once __DIR__ . '/../config/config.php';

class Database {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(HOST, USER, PASSWORD, DB);
        if ($this->conn->connect_error) {
            die("❌ Kết nối thất bại: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8mb4");
    }

    // Helper: build placeholders like "?, ?, ?"
    private function buildPlaceholders($count) {
        if ($count <= 0) return '';
        return implode(',', array_fill(0, $count, '?'));
    }

    // Helper: infer bind types from PHP values
    private function inferTypes($params) {
        $types = '';
        foreach ($params as $p) {
            if (is_int($p)) $types .= 'i';
            elseif (is_float($p)) $types .= 'd';
            else $types .= 's';
        }
        return $types;
    }

    // SELECT (trả về dữ liệu)
    public function callProcedureSelect($procedureName, $params = []) {
        $placeholders = $this->buildPlaceholders(count($params));
        $sql = "CALL {$procedureName}(" . $placeholders . ")";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Không thể prepare: " . $this->conn->error);
        }
        if (!empty($params)) {
            $types = $this->inferTypes($params);
            $stmt->bind_param($types, ...$params);
        }
        if (!$stmt->execute()) {
            throw new Exception("Lỗi khi gọi {$procedureName}: " . $stmt->error);
        }
        $result = $stmt->get_result();
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $result->free();
        }
        // Flush remaining results from PROC
        while ($this->conn->more_results() && $this->conn->next_result()) { /* flush */ }
        $stmt->close();
        return $rows;
    }

    // INSERT/UPDATE/DELETE (không trả dữ liệu)
    public function callProcedureExecute($procedureName, $params = []) {
        $placeholders = $this->buildPlaceholders(count($params));
        $sql = "CALL {$procedureName}(" . $placeholders . ")";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Không thể prepare: " . $this->conn->error);
        }
        if (!empty($params)) {
            $types = $this->inferTypes($params);
            $stmt->bind_param($types, ...$params);
        }
        $ok = $stmt->execute();
        $err = $ok ? null : $stmt->error;
        // Flush results if any
        while ($this->conn->more_results() && $this->conn->next_result()) { /* flush */ }
        $stmt->close();
        if (!$ok) {
            throw new Exception("Lỗi khi gọi {$procedureName}: " . $err);
        }
        return true;
    }

    // Optional raw helpers (không bắt buộc dùng): vẫn không tạo file mới
    public function select($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) throw new Exception($this->conn->error);
        if (!empty($params)) {
            $types = $this->inferTypes($params);
            $stmt->bind_param($types, ...$params);
        }
        if (!$stmt->execute()) throw new Exception($stmt->error);
        $res = $stmt->get_result();
        $rows = [];
        if ($res) {
            while ($r = $res->fetch_assoc()) $rows[] = $r;
            $res->free();
        }
        $stmt->close();
        return $rows;
    }

    public function execute($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) throw new Exception($this->conn->error);
        if (!empty($params)) {
            $types = $this->inferTypes($params);
            $stmt->bind_param($types, ...$params);
        }
        if (!$stmt->execute()) throw new Exception($stmt->error);
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }

    public function __destruct() {
        if ($this->conn) $this->conn->close();
    }
}
?>
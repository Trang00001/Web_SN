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

    /**
     * Get the mysqli connection object
     * @return mysqli
     */
    public function getConnection() {
        return $this->conn;
    }

    // SELECT (trả về dữ liệu)
    public function callProcedureSelect($procedureName, $params = []) {
        $placeholders = $params ? implode(',', array_fill(0, count($params), '?')) : '';
        $sql = "CALL $procedureName($placeholders)";
        $stmt = $this->conn->prepare($sql);

        if ($params) {
            $types = str_repeat('s', count($params)); // tất cả dạng string
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        $stmt->close();
        return $data;
    }

    // INSERT / UPDATE / DELETE (không trả dữ liệu)
    public function callProcedureExecute($procedureName, $params = []) {
        $placeholders = $params ? implode(',', array_fill(0, count($params), '?')) : '';
        $sql = "CALL $procedureName($placeholders)";
        $stmt = $this->conn->prepare($sql);

        if ($params) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        $success = $stmt->execute();
        if (!$success) {
            error_log("❌ Lỗi khi gọi $procedureName: " . $stmt->error);
        }
        $stmt->close();
        return $success;
    }

    // Call stored procedure with OUT parameter
    public function callProcedureWithOutParam($procedureName, $params = []) {
        // Add placeholder for OUT parameter
        $inParams = array_fill(0, count($params), '?');
        $inParams[] = '@out_param';
        $placeholders = implode(',', $inParams);
        
        $sql = "CALL $procedureName($placeholders)";
        $stmt = $this->conn->prepare($sql);

        if ($params) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        $success = $stmt->execute();
        $stmt->close();
        
        if ($success) {
            // Get OUT parameter value
            $result = $this->conn->query("SELECT @out_param AS out_param");
            if ($result) {
                $row = $result->fetch_assoc();
                return ['success' => true, 'out_param' => $row['out_param']];
            }
        }
        
        error_log("❌ Lỗi khi gọi $procedureName: " . $this->conn->error);
        return ['success' => false];
    }

    public function __destruct() {
        if ($this->conn) $this->conn->close();
    }
}
?>

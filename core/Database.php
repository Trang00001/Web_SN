<?php
require_once __DIR__ . '/../config/config.php';

class Database {
    public $conn;

    public function __construct() {
        $this->conn = mysqli_connect(HOST, USER, PASSWORD, DB);
        if (!$this->conn) {
            die("Kết nối thất bại: " . mysqli_connect_error());
        }
        mysqli_set_charset($this->conn, "utf8");
    }

    // Thực thi SELECT, trả về mảng dữ liệu
    public function select($query) {
        $result = mysqli_query($this->conn, $query);
        $data = [];
        if ($result) {
            while($row = mysqli_fetch_assoc($result)) $data[] = $row;
            mysqli_free_result($result);
        } else {
            echo "Lỗi truy vấn: " . mysqli_error($this->conn);
        }
        return $data;
    }

    // Thực thi INSERT, UPDATE, DELETE
    public function execute($query) {
        $result = mysqli_query($this->conn, $query);
        if (!$result) {
            echo "Lỗi truy vấn: " . mysqli_error($this->conn);
            return false;
        }
        return true;
    }

    // Đóng kết nối tự động
    public function __destruct() {
        mysqli_close($this->conn);
    }
}
?>

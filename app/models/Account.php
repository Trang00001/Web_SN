<?php
require_once __DIR__ . '/../../core/Database.php';

class Account {
    private $accountID;
    private $email;
    private $passwordHash;
    private $username;
    private $db;

    public function __construct($email = null, $passwordHash = null, $username = null, $accountID = null) {
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->username = $username;
        $this->accountID = $accountID;
        $this->db = new Database();
    }

    // -------- Getters/Setters --------
    public function getId(){ return $this->accountID; }
    public function setId($id){ $this->accountID = $id; return $this; }

    public function getEmail(){ return $this->email; }
    public function setEmail($email){ $this->email = $email; return $this; }

    public function getUsername(){ return $this->username; }
    public function setUsername($username){ $this->username = $username; return $this; }

    public function getPasswordHash(){ return $this->passwordHash; }
    public function setPasswordHash($hash){ $this->passwordHash = $hash; return $this; }

    // -------- Core actions --------

    // Đăng ký bằng stored procedure
    public function register() {
        return $this->db->callProcedureExecute("sp_RegisterUser", [
            $this->email, $this->passwordHash, $this->username
        ]);
    }

    // Đăng nhập: trả về bản ghi người dùng nếu đúng
    // Gợi ý: nếu CSDL có sp_LoginUser, dùng trực tiếp; nếu không, fallback truy vấn thường
    public function login() {
        // Thử dùng stored procedure nếu có
        try {
            $rows = $this->db->callProcedureSelect("sp_LoginUser", [
                $this->email, $this->passwordHash
            ]);
            return $rows;
        } catch (Exception $e) {
            // Fallback: lấy user theo email để nơi gọi tự password_verify
            $rows = $this->db->select("SELECT * FROM Account WHERE Email = ?", [$this->email]);
            return $rows;
        }
    }

    // Lấy user theo email
    public function findByEmail($email) {
        return $this->db->select("SELECT * FROM Account WHERE Email = ?", [$email]);
    }

    // Lấy user theo ID
    public function getAccountById($accountID) {
        $rows = $this->db->select("SELECT * FROM Account WHERE AccountID = ?", [$accountID]);
        return $rows ? $rows[0] : null;
    }

    // Cập nhật mật khẩu (bằng email)
    public function updatePasswordByEmail($email, $passwordHash) {
        return $this->db->execute("UPDATE Account SET PasswordHash = ? WHERE Email = ?", [
            $passwordHash, $email
        ]);
    }

    // Xoá tài khoản
    public function delete() {
        // nếu có sp_DeleteUser thì gọi; nếu không fallback
        try {
            return $this->db->callProcedureExecute("sp_DeleteUser", [$this->accountID]);
        } catch (Exception $e) {
            return $this->db->execute("DELETE FROM Account WHERE AccountID = ?", [$this->accountID]);
        }
    }
}
?>
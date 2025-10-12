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

    // GETTER & SETTER
    public function getAccountID() { return $this->accountID; }
    public function getEmail() { return $this->email; }
    public function getPasswordHash() { return $this->passwordHash; }
    public function getUsername() { return $this->username; }

    public function setAccountID($accountID) { $this->accountID = $accountID; }
    public function setEmail($email) { $this->email = $email; }
    public function setPasswordHash($passwordHash) { $this->passwordHash = $passwordHash; }
    public function setUsername($username) { $this->username = $username; }

    // FUNCTIONS
    public function register() {
        return $this->db->callProcedureExecute("sp_RegisterUser", [
            $this->email, $this->passwordHash, $this->username
        ]);
    }

    public function login() {
        return $this->db->callProcedureSelect("sp_LoginUser", [
            $this->email, $this->passwordHash
        ]);
    }

    public function delete() {
        return $this->db->callProcedureExecute("sp_DeleteUser", [$this->accountID]);
    }
}
?>

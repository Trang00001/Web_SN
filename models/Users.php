<?php
// Users.php
class Users {
    public int $userId;
    public string $username;
    public string $password;

    public function __construct($userId, $username, $password) {
        $this->userId = $userId;
        $this->username = $username;
        $this->password = $password;
    }

    public function register() {
        // logic đăng ký
    }

    public function login() {
        // logic đăng nhập
    }

    public function updateProfile() {
        // logic cập nhật hồ sơ
    }
}

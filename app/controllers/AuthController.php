<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Helpers.php';

class AuthController {
    private string $assetBase;

    public function __construct() {
        $this->assetBase = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/assets';
        ensure_session_started();
    }

    public function showLogin() {
        $BASE_ASSETS = $this->assetBase; // variable for views
        // generate csrf for the page forms
        csrf_token();
        include __DIR__ . '/../views/pages/auth/login.php';
    }

    public function showRegister() {
        $BASE_ASSETS = $this->assetBase;
        csrf_token();
        include __DIR__ . '/../views/pages/auth/register.php';
    }

    public function register() {
        ensure_session_started();
        if (!csrf_validate($_POST['_token'] ?? null)) {
            http_response_code(419);
            echo "CSRF token không hợp lệ.";
            return;
        }
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $confirm  = (string)($_POST['confirm'] ?? '');

        if (!$username || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6 || $password !== $confirm) {
            echo "Dữ liệu không hợp lệ."; return;
        }

        $u = new User();
        try {
            $ok = $u->create($username, $email, $password);
            if ($ok) {
                $_SESSION['flash'] = "Đăng ký thành công. Vui lòng đăng nhập.";
                redirect('/auth/login');
            } else {
                echo "Không thể tạo tài khoản.";
            }
        } catch (Exception $e) {
            echo "Lỗi: " . htmlspecialchars($e->getMessage());
        }
    }

    public function login() {
        ensure_session_started();
        if (!csrf_validate($_POST['_token'] ?? null)) {
            http_response_code(419);
            echo "CSRF token không hợp lệ.";
            return;
        }
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            echo "Thông tin đăng nhập không hợp lệ."; return;
        }

        $model = new User();
        $user = $model->findByEmail($email);
        if (!$user || !password_verify($password, $user->password_hash)) {
            echo "Email hoặc mật khẩu không đúng."; return;
        }
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->username;
        redirect('/profile');
    }

    public function logout() {
        ensure_session_started();
        session_destroy();
        redirect('/auth/login');
    }
}

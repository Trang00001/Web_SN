<?php
// Use Account model (the project doesn't include User.php)
require_once __DIR__ . '/../models/Account.php';

// Helpers fallbacks (project refers to app/core/Helpers.php but file not present)
if (!function_exists('ensure_session_started')) {
    function ensure_session_started(){
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
if (!function_exists('redirect')) {
    function redirect($path){
        // Allow absolute and relative
        header("Location: " . $path);
        exit;
    }
}
if (!function_exists('csrf_token')) {
    function csrf_token(){
        ensure_session_started();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
        }
        return $_SESSION['csrf_token'];
    }
}
if (!function_exists('check_csrf')) {
    function check_csrf($token){
        ensure_session_started();
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token ?? '');
    }
}

class AuthController {
    private string $assetBase;

    public function __construct() {
        $this->assetBase = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/assets';
        ensure_session_started();
    }

    // -------- Views --------
    public function showLogin() {
        $BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/..');
        $BASE_ASSETS = $this->assetBase;
        csrf_token();
        $title = "Đăng nhập";
        require __DIR__ . '/../views/pages/auth/login.php';
    }

    public function showRegister() {
        $BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/..');
        $BASE_ASSETS = $this->assetBase;
        csrf_token();
        $title = "Đăng ký";
        require __DIR__ . '/../views/pages/auth/register.php';
    }

    public function showForgot() {
        $BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/..');
        $BASE_ASSETS = $this->assetBase;
        csrf_token();
        $title = "Quên mật khẩu";
        $forgotView = __DIR__ . '/../views/pages/auth/forgot_password.php';
        if (file_exists($forgotView)) {
            require $forgotView;
        } else {
            echo "<h3>Quên mật khẩu</h3><form method='post' action='/auth/forgot'>
                    <input type='hidden' name='csrf' value='".htmlspecialchars($_SESSION['csrf_token'])."'>
                    <div><label>Email</label><input type='email' name='email' required></div>
                    <div><label>Mật khẩu mới</label><input type='password' name='new_password' required minlength='6'></div>
                    <div><label>Nhập lại</label><input type='password' name='confirm_password' required minlength='6'></div>
                    <button type='submit'>Đặt lại mật khẩu</button>
                  </form>";
        }
    }

    // -------- Actions --------
    public function register() {
        ensure_session_started();
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $csrf = $_POST['csrf'] ?? $_POST['csrf_token'] ?? '';

        if (!check_csrf($csrf)) {
            http_response_code(400);
            echo "CSRF token không hợp lệ."; return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { echo "Email không hợp lệ"; return; }
        if (strlen($username) < 3) { echo "Username tối thiểu 3 ký tự"; return; }
        if (strlen($password) < 6) { echo "Mật khẩu tối thiểu 6 ký tự"; return; }
        if ($password !== $confirm) { echo "Mật khẩu nhập lại không khớp"; return; }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $acc = new Account($email, $hash, $username);
        try {
            $acc->register();
        } catch (Exception $e) {
            // Duplicate email/username
            echo "Không thể đăng ký: " . htmlspecialchars($e->getMessage()); return;
        }

        // Auto login after register
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name']  = $username;
        redirect('/auth/login');
    }

    public function login() {
        ensure_session_started();
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $csrf = $_POST['csrf'] ?? $_POST['csrf_token'] ?? '';

        if (!check_csrf($csrf)) {
            http_response_code(400);
            echo "CSRF token không hợp lệ."; return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { echo "Email không hợp lệ"; return; }
        if ($password === '') { echo "Vui lòng nhập mật khẩu"; return; }

        $acc = new Account();
        $rows = $acc->findByEmail($email);
        $user = $rows[0] ?? null;

        if (!$user || !password_verify($password, $user['PasswordHash'])) {
            echo "Email hoặc mật khẩu không đúng."; return;
        }
        $_SESSION['user_id'] = (int)$user['AccountID'];
        $_SESSION['user_name'] = $user['Username'];
        $_SESSION['user_email'] = $user['Email'];
        redirect('/home');
    }

    public function forgot() {
        ensure_session_started();
        $email = trim($_POST['email'] ?? '');
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $csrf = $_POST['csrf'] ?? $_POST['csrf_token'] ?? '';

        if (!check_csrf($csrf)) {
            http_response_code(400);
            echo "CSRF token không hợp lệ."; return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { echo "Email không hợp lệ"; return; }
        if (strlen($new) < 6) { echo "Mật khẩu tối thiểu 6 ký tự"; return; }
        if ($new !== $confirm) { echo "Mật khẩu nhập lại không khớp"; return; }

        $acc = new Account();
        $found = $acc->findByEmail($email);
        if (!$found) { echo "Không tìm thấy tài khoản với email này."; return; }

        $hash = password_hash($new, PASSWORD_DEFAULT);
        $updated = $acc->updatePasswordByEmail($email, $hash);
        if ($updated <= 0) {
            echo "Đặt lại mật khẩu thất bại."; return;
        }
        redirect('/auth/login');
    }

    public function logout() {
        ensure_session_started();
        session_destroy();
        echo "Đã đăng xuất";
    }
}

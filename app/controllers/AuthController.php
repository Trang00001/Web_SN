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
        $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
        $this->assetBase = rtrim($scriptPath, '/') . '/assets';
        ensure_session_started();
    }

    // -------- Views --------
    public function showLogin() {
        $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
        $BASE = rtrim($scriptPath, '/');
        $BASE = dirname($BASE); // Go up one level
        $BASE_ASSETS = $this->assetBase;
        csrf_token();
        $title = "Đăng nhập";
        require __DIR__ . '/../views/pages/auth/login.php';
    }

    public function showRegister() {
        $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
        $BASE = rtrim($scriptPath, '/');
        $BASE = dirname($BASE); // Go up one level
        $BASE_ASSETS = $this->assetBase;
        csrf_token();
        $title = "Đăng ký";
        require __DIR__ . '/../views/pages/auth/register.php';
    }

    public function showForgot() {
        $BASE = dirname(dirname($_SERVER['SCRIPT_NAME']));
        $BASE = rtrim($BASE, '/');
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
        
        $response = ['success' => false, 'message' => ''];

        if (!check_csrf($csrf)) {
            http_response_code(400);
            $response['message'] = "CSRF token không hợp lệ.";
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = "Email không hợp lệ";
        }
        else if (strlen($username) < 3) {
            $response['message'] = "Username tối thiểu 3 ký tự";
        }
        else if (strlen($password) < 6) {
            $response['message'] = "Mật khẩu tối thiểu 6 ký tự";
        }
        else if ($password !== $confirm) {
            $response['message'] = "Mật khẩu nhập lại không khớp";
        }
        else {
            try {
                // Normalize email (lowercase, trim)
                $email = strtolower(trim($email));
                $username = trim($username);
                
                // Kiểm tra email/username đã tồn tại trước khi đăng ký
                $acc = new Account();
                
                // Kiểm tra email - sử dụng LOWER() trong SQL để case-insensitive
                $existingEmail = $acc->findByEmail($email);
                
                // Debug: Log để kiểm tra
                if (!empty($existingEmail) && is_array($existingEmail)) {
                    error_log("Email exists check - Found: " . json_encode($existingEmail[0] ?? []));
                }
                
                if (!empty($existingEmail) && is_array($existingEmail) && count($existingEmail) > 0) {
                    $response['message'] = "Email này đã được sử dụng.";
                } else {
                    // Kiểm tra username (case-insensitive)
                    $existingUsername = $acc->findByUsername($username);
                    
                    if (!empty($existingUsername) && is_array($existingUsername) && count($existingUsername) > 0) {
                        $response['message'] = "Tên người dùng này đã được sử dụng.";
                    } else {
                        // Double check: Query trực tiếp để chắc chắn
                        require_once __DIR__ . '/../../core/Database.php';
                        $db = new Database();
                        $doubleCheckEmail = $db->select("SELECT AccountID FROM Account WHERE LOWER(Email) = ?", [$email]);
                        $doubleCheckUsername = $db->select("SELECT AccountID FROM Account WHERE LOWER(Username) = ?", [strtolower($username)]);
                        
                        if (!empty($doubleCheckEmail) && count($doubleCheckEmail) > 0) {
                            $response['message'] = "Email này đã được sử dụng.";
                        } elseif (!empty($doubleCheckUsername) && count($doubleCheckUsername) > 0) {
                            $response['message'] = "Tên người dùng này đã được sử dụng.";
                        } else {
                            // Đăng ký tài khoản mới
                            $hash = password_hash($password, PASSWORD_DEFAULT);
                            $acc = new Account($email, $hash, $username);
                            
                            try {
                                $acc->register();
                                
                                // Auto login after register
                                $_SESSION['user_email'] = $email;
                                $_SESSION['user_name']  = $username;
                                
                                $response['success'] = true;
                                $response['message'] = "Đăng ký thành công!";
                                $response['redirect'] = (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '') . '/login';
                            } catch (Exception $regException) {
                                // If stored procedure throws exception, it means email/username exists
                                $regMsg = $regException->getMessage();
                                
                                if (stripos($regMsg, 'Email or username already exists') !== false || 
                                    stripos($regMsg, 'Duplicate entry') !== false) {
                                    $response['message'] = "Email hoặc tên người dùng đã tồn tại.";
                                } else {
                                    throw $regException; // Re-throw if it's a different error
                                }
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                $msg = $e->getMessage();
                if (stripos($msg, 'Email or username already exists') !== false || 
                    stripos($msg, 'Duplicate entry') !== false ||
                    stripos($msg, 'already exists') !== false) {
                    $response['message'] = "Email hoặc tên người dùng đã tồn tại.";
                } else {
                    $response['message'] = "Không thể đăng ký: " . htmlspecialchars($msg);
                }
            }
        }
        
        // Check if this is an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } else {
            // If not AJAX, redirect back to register page with error message
            if (!$response['success']) {
                $_SESSION['register_error'] = $response['message'];
            } else {
                $_SESSION['register_success'] = $response['message'];
                if (isset($response['redirect'])) {
                    header('Location: ' . $response['redirect']);
                    exit;
                }
            }
            header('Location: /register');
            exit;
        }
    }

    public function login() {
        ensure_session_started();
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $csrf = $_POST['csrf'] ?? $_POST['csrf_token'] ?? '';
        
        $response = ['success' => false, 'message' => ''];

        if (!check_csrf($csrf)) {
            http_response_code(400);
            $response['message'] = "CSRF token không hợp lệ.";
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = "Email không hợp lệ";
        }
        else if ($password === '') {
            $response['message'] = "Vui lòng nhập mật khẩu";
        }
        else {
            $acc = new Account();
            $rows = $acc->findByEmail($email);
            $user = $rows[0] ?? null;

            // Debug logging
            error_log("Login attempt for email: {$email}");
            error_log("User found: " . ($user ? 'YES' : 'NO'));
            if ($user) {
                error_log("User data: AccountID={$user['AccountID']}, Username={$user['Username']}, Email={$user['Email']}");
                error_log("PasswordHash from DB: {$user['PasswordHash']}");
                error_log("Password from form: {$password}");
            }

            if (!$user) {
                $response['message'] = "Email hoặc mật khẩu không đúng.";
            } else {
                // Check if password is hashed or plain text
                $passwordMatch = false;
                
                // Try hashed password first
                if (password_verify($password, $user['PasswordHash'])) {
                    $passwordMatch = true;
                    error_log("✅ Password verified with password_verify()");
                }
                // Fallback: check plain text (for testing/development)
                else if ($password === $user['PasswordHash']) {
                    $passwordMatch = true;
                    error_log("⚠️ Plain text password match for user: {$email}");
                }
                
                if (!$passwordMatch) {
                    error_log("❌ Password does not match");
                    $response['message'] = "Email hoặc mật khẩu không đúng.";
                } else {
                    $_SESSION['user_id'] = (int)$user['AccountID'];
                    $_SESSION['user_name'] = $user['Username'];
                    $_SESSION['user_email'] = $user['Email'];
                    
                    error_log("✅ Login successful for user_id: {$_SESSION['user_id']}");
                    
                    $response['success'] = true;
                    $response['message'] = "Đăng nhập thành công!";
                    $response['redirect'] = (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '') . '/home';
                }
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }

public function resetPassword() {
    ensure_session_started();

    $email = trim($_POST['email'] ?? '');
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $csrf = $_POST['csrf'] ?? $_POST['csrf_token'] ?? '';

    $msg = "";
    $redirect = null;
    $isSuccess = false;

    if (!check_csrf($csrf)) {
        $msg = "⚠️ CSRF token không hợp lệ.";
    } 
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "⚠️ Email không hợp lệ.";
    } 
    else if (strlen($new) < 6) {
        $msg = "⚠️ Mật khẩu tối thiểu 6 ký tự.";
    } 
    else if ($new !== $confirm) {
        $msg = "⚠️ Mật khẩu nhập lại không khớp.";
    } 
    else {
        try {
            $acc = new Account();
            $found = $acc->findByEmail($email);

            if (!$found) {
                $msg = "❌ Không tìm thấy tài khoản với email này.";
            } else {
                $hash = password_hash($new, PASSWORD_DEFAULT);
                $updated = $acc->updatePasswordByEmail($email, $hash);

                if ($updated <= 0) {
                    $msg = "❌ Đặt lại mật khẩu thất bại. Vui lòng thử lại.";
                } else {
                    $msg = "✅ Đặt lại mật khẩu thành công! Bạn sẽ được chuyển đến trang đăng nhập trong 5 giây...";
                    $redirect = '/login';
                    $isSuccess = true;
                }
            }
        } catch (Exception $e) {
            $msg = "❌ Lỗi: " . htmlspecialchars($e->getMessage());
        }
    }
include __DIR__ . '/../views/components/auth/notification.php';

}









    public function logout() {
        ensure_session_started();
        $_SESSION = array(); // Clear all session data
        
        // Delete session cookie if it exists
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
        
        session_destroy();
        
        // Redirect to login
        header('Location: /login');
        exit();
    }
}

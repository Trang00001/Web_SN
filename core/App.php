<?php 
/**
 * App.php
 * Bộ điều hướng trung tâm (Front Controller) cho ứng dụng MVC.
 * Tự động phân tích URL => Controller => Method => Params
 */

class App {
    protected $controller = 'PostController';  // Controller mặc định
    protected $method = 'list';                // Method mặc định
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Xác định Controller
        if (!empty($url) && file_exists('../app/controllers/' . ucfirst($url[0]) . '.php')) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. Xác định Method
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        } else {
            // Nếu method không tồn tại -> báo lỗi thân thiện
            if (isset($url[1])) {
                http_response_code(404);
                die("⚠️ Method '{$url[1]}' không tồn tại trong {$this->controller}");
            }
        }

        // 3. Lấy tham số còn lại (nếu có)
        $this->params = $url ? array_values($url) : [];

        // 4. Gọi Controller + Method + Params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Phân tích URL từ query string (?url=controller/method/params)
     * @return array
     */
    private function parseUrl() {
        if (isset($_GET['url'])) {
            $url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
}
?>

<?php
class App {
    protected $controller = 'PostController'; // Controller mặc định
    protected $method = 'list';             // Method mặc định
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Xác định Controller
        if (file_exists('../app/controllers/' . $url[0] . '.php')) {
            $this->controller = $url[0];
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Xác định Method
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        // Các tham số còn lại
        $this->params = $url ? array_values($url) : [];

        // Gọi Controller và Method
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
?>

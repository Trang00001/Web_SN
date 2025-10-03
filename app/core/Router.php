<?php
// app/core/Router.php
class Router {
    private array $routes = [];

    public function get(string $path, callable|array $handler) { $this->add('GET', $path, $handler); }
    public function post(string $path, callable|array $handler) { $this->add('POST', $path, $handler); }

    private function add(string $method, string $path, $handler) {
        $this->routes[] = compact('method', 'path', 'handler');
    }

    public function dispatch(string $uri, string $method) {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        foreach ($this->routes as $r) {
            if ($r['method'] === $method && rtrim($r['path'], '/') === rtrim($path, '/')) {
                return $this->invoke($r['handler']);
            }
        }
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
    }

    private function invoke($handler) {
        if (is_callable($handler)) return $handler();
        if (is_array($handler)) {
            [$class, $method] = $handler;
            if (!class_exists($class)) throw new Exception("Controller $class not found");
            $obj = new $class();
            if (!method_exists($obj, $method)) throw new Exception("Method $class::$method not found");
            return $obj->$method();
        }
        throw new Exception("Invalid route handler");
    }
}

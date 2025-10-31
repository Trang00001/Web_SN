<?php
class Router {
    private array $routes = [];
    
    public function get($path, $handler) {
        $this->addRoute('GET', $path, $handler);
    }
    
    public function post($path, $handler) {
        $this->addRoute('POST', $path, $handler);
    }
    
    private function addRoute($method, $path, $handler) {
        // Convert URL parameters to regex pattern
        $pattern = preg_replace('/:[a-zA-Z]+/', '([^/]+)', $path);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';
        
        $this->routes[$method][] = [
            'pattern' => $pattern,
            'handler' => $handler,
            'path' => $path
        ];
    }
    
    public function dispatch($uri, $method = 'GET') {
        $method = strtoupper($method);
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        
        if (!isset($this->routes[$method])) {
            $this->send404();
            return;
        }
        
        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $path, $matches)) {
                array_shift($matches); // Remove full match
                
                try {
                    $handler = $route['handler'];
                    if (is_callable($handler)) {
                        call_user_func_array($handler, $matches);
                        return;
                    }
                    
                    if (is_array($handler) && count($handler) === 2) {
                        [$class, $method] = $handler;
                        $obj = new $class();
                        call_user_func_array([$obj, $method], $matches);
                        return;
                    }
                } catch (Exception $e) {
                    error_log($e->getMessage());
                    $this->send500();
                    return;
                }
            }
        }
        
        $this->send404();
    }
    
    private function send404() {
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
        echo "<p>The requested URL was not found on this server.</p>";
    }
    
    private function send500() {
        http_response_code(500);
        echo "<h1>500 Internal Server Error</h1>";
        echo "<p>The server encountered an error processing your request.</p>";
    }
}
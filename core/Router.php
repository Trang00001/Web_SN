<?php
class Router {
    private array $routes = [];
    private array $staticRoutes = []; // Direct path mapping
    private string $basePath = '';
    
    public function __construct($basePath = '') {
        $this->basePath = rtrim($basePath, '/');
    }
    
    public function get($path, $handler) {
        $this->addRoute('GET', $path, $handler);
    }
    
    public function post($path, $handler) {
        $this->addRoute('POST', $path, $handler);
    }
    
    /**
     * Add a static route (direct file mapping)
     */
    public function addStatic($path, $file) {
        $this->staticRoutes[$path] = $file;
    }
    
    /**
     * Add multiple static routes at once
     */
    public function addStaticRoutes(array $routes) {
        foreach ($routes as $path => $file) {
            $this->addStatic($path, $file);
        }
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
        
        // Remove /public/ prefix if exists
        $path = preg_replace('#^/public#', '', $path);
        
        // Check static routes first
        if (isset($this->staticRoutes[$path])) {
            $this->serveFile($this->basePath . $this->staticRoutes[$path]);
            return;
        }
        
        // Check dynamic routes
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route) {
                if (preg_match($route['pattern'], $path, $matches)) {
                    array_shift($matches); // Remove full match
                    
                    try {
                        $handler = $route['handler'];
                        
                        // Callable function
                        if (is_callable($handler)) {
                            call_user_func_array($handler, $matches);
                            return;
                        }
                        
                        // [Class, method] array
                        if (is_array($handler) && count($handler) === 2) {
                            [$class, $method] = $handler;
                            $obj = new $class();
                            call_user_func_array([$obj, $method], $matches);
                            return;
                        }
                        
                        // File path string
                        if (is_string($handler)) {
                            $this->serveFile($this->basePath . $handler);
                            return;
                        }
                    } catch (Exception $e) {
                        error_log("Router Error: " . $e->getMessage());
                        $this->send500($e->getMessage());
                        return;
                    }
                }
            }
        }
        
        $this->send404($path);
    }
    
    private function serveFile($file) {
        if (file_exists($file)) {
            chdir(dirname($file));
            require $file;
        } else {
            $this->send404($file);
        }
    }
    
    private function send404($path = '') {
        http_response_code(404);
        echo "<!DOCTYPE html><html><head><title>404 Not Found</title>";
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
        echo "</head><body class='bg-light'>";
        echo "<div class='container mt-5'><div class='alert alert-danger'>";
        echo "<h1 class='h4'>404 Not Found</h1>";
        echo "<p>The requested resource <code>" . htmlspecialchars($path) . "</code> was not found.</p>";
        echo "<hr><a href='/' class='btn btn-primary'>Go to homepage</a>";
        echo "</div></div></body></html>";
    }
    
    private function send500($message = '') {
        http_response_code(500);
        echo "<!DOCTYPE html><html><head><title>500 Internal Server Error</title>";
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
        echo "</head><body class='bg-light'>";
        echo "<div class='container mt-5'><div class='alert alert-danger'>";
        echo "<h1 class='h4'>500 Internal Server Error</h1>";
        echo "<p>The server encountered an error processing your request.</p>";
        if ($message && ini_get('display_errors')) {
            echo "<hr><small><code>" . htmlspecialchars($message) . "</code></small>";
        }
        echo "</div></div></body></html>";
    }
}
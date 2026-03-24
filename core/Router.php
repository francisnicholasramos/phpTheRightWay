<?php 

namespace Core;

class Router {
    private array $routes = [];

    public function add(string $method, string $path, array $handler): void {
        $this->routes["{$method} {$path}"] = $handler;
    }

    public function dispatch(string $method, string $path): void {
        $key = "{$method} {$path}";

        if (isset($this->routes[$key])) {
            [$controller, $action] = $this->routes[$key];
            (new $controller())->$action();
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}

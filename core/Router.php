<?php 

namespace Core;

use Core\Route;

class Router {
    private static array $routes = [];

    public static function get(string $path, array $handler): Route {
        $route = new Route('GET', $path, $handler);
        self::$routes["GET {$path}"] = $route;
        return $route;
    }

    public static function post(string $path, array $handler): Route {
        $route = new Route('POST', $path, $handler);
        self::$routes["POST {$path}"] = $route;
        return route;
    }

    public function dispatch(string $method, string $path): void {
        $key = "{$method} {$path}";

        if (isset(self::$routes[$key])) {
            $route = self::$routes[$key];

            if ($route->getMiddleware()) {
                $middlewareClass = "App\\Middleware\\" . $route->getMiddleware();
                (new $middlewareClass())->handle();
            }

            [$controller, $action] = $route->getHandler();
            (new $controller())->$action();
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}

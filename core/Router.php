<?php 

namespace Core;

use Core\Route;

class Router {
    private static array $routes = [];

    public static function get(string $path, array $handler): Route {
        $route = new Route('GET', $path, $handler);
        self::$routes[] = $route;
        return $route;
    }

    public static function post(string $path, array $handler): Route {
        $route = new Route('POST', $path, $handler);
        self::$routes[] = $route;
        return $route;
    }

    public function dispatch(string $method, string $path): void {
        foreach (self::$routes as $route) {
            if ($route->getMethod() !== $method) {
                continue;
            }

            $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)',
                $route->getPath());
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // remove full match

                if ($route->getMiddleware()) {
                    $middlewareClass = "App\\Middleware\\" .
                        $route->getMiddleware();
                    (new $middlewareClass())->handle();
                }

                [$controller, $action] = $route->getHandler();
                (new $controller())->$action(...$matches);
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}

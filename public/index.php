<?php 

require __DIR__ . '/../vendor/autoload.php';

use Core\Router;

$router = new Router();

$routes = require __DIR__ . '/../routes/web.php';

foreach ($routes as $route => $handler) {
    [$method, $path] = explode(' ', $route, 2);
    $router->add($method, $path, $handler);
}

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

<?php 

require __DIR__ . '/../vendor/autoload.php';

use Core\Router;
use Core\Session;

new Session();

$router = new Router();

require __DIR__ . '/../routes/web.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

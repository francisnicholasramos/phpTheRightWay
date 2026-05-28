<?php 

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Core\Router;
use Core\Session;

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createMutable(__DIR__ . '/..');
    $dotenv->load();
}

new Session();

$router = new Router();

require __DIR__ . '/../routes/web.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

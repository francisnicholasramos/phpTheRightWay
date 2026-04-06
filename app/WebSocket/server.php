<?php

require __DIR__ . '/../../vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\WebSocket\Chat;

$server = IoServer::factory(
    new Chat(),
    8080
);

$server->run();

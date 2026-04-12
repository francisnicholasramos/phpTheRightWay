<?php

require __DIR__ . '/../../vendor/autoload.php';

use Ratchet\Server\IoServer;
use App\WebSocket\WebSocketHandler;

$server = IoServer::factory(
    new WebSocketHandler(),
    8080
);

$server->run();

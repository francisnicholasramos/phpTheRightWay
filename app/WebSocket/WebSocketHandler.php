<?php 

namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketHandler implements MessageComponentInterface {
    protected $clients;
    protected static $allClients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        self::$allClients = $this->clients;
    }

    public function onOpen(ConnectionInterface $conn): void {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg): void {
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn): void {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e): void {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * @param array{type: string, post_id: string|int, count: int} $data
     */
    public static function broadcast(array $data): void {
        $msg = json_encode($data);
        if (!empty(self::$allClients)) {
            foreach (self::$allClients as $client) {
                $client->send($msg);
            }
        }
    }
}

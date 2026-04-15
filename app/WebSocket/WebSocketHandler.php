<?php 

namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketHandler implements MessageComponentInterface {
    protected $clients;
    protected static $instance = null;
    protected static $userConnections = []; // Maps user_id to connection

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn): void {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg): void {
        $data = json_decode($msg, true);
        
        // Only process if valid JSON
        if (!is_array($data)) {
            return;
        }
        
        // Handle authentication message
        if (isset($data['type']) && $data['type'] === 'authenticate' && isset($data['user_id'])) {
            self::$userConnections[$data['user_id']] = $from;
            echo "User {$data['user_id']} authenticated on connection {$from->resourceId}\n";
            return;
        }
        
        // Broadcast other messages
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn): void {
        $this->clients->detach($conn);
        
        // Remove from user connections mapping
        foreach (self::$userConnections as $user_id => $connection) {
            if ($connection === $conn) {
                unset(self::$userConnections[$user_id]);
                echo "User {$user_id} disconnected\n";
            }
        }
        
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
        $instance = self::getInstance();
        $msg = json_encode($data);
        if (!empty($instance->clients)) {
            foreach ($instance->clients as $client) {
                $client->send($msg);
            }
        }
    }

    /**
     * Send message to specific user
     * @param string $user_id
     * @param array $data
     */
    public static function sendToUser(string $user_id, array $data): void {
        
        if (isset(self::$userConnections[$user_id])) {
            $msg = json_encode($data);
            self::$userConnections[$user_id]->send($msg);
        } else {
        }
    }
}


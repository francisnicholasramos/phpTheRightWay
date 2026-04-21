<?php

namespace App\Models;

class Chat extends Model {
    protected string $table = 'chats';

    public readonly string $id;
    public readonly ?string $name;
    public readonly string $chat_type;
    public readonly string $created_at;

    /**
     * @param array $row
     */
    private function hydrate(array $row): self {
        $chats                = new self();
        $chats->id            = $row['id'];
        $chats->name          = $row['name'];
        $chats->chat_type     = $row['chat_type'];
        $chats->created_at    = $row['created_at'];

        return $chats;
    }

    /**
     * @return App\Models\Chats[]
     */
    public function getUserChats(string $user_id): array {
        $stmt = $this->pdo->prepare("
            SELECT c.* from {$this->table} c
            JOIN chat_participants cp ON cp.chat_id = c.id
            WHERE cp.user_id = :user_id
            ORDER BY c.created_at DESC
        ");

        $stmt->execute(['user_id' => $user_id]);
        return array_map(fn($row) => $this->hydrate($row), $stmt->fetchAll());
    }

    public function createDirectChat() {
        $stmt = $this->pdo->prepare("
            insert into {$this->table} (chat_type)
            values ('direct')
        ");

        $stmt->execute();
    }
}

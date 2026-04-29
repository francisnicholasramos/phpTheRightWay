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

    public function createDirectChat(string $userA, string $userB): string|false {
        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO {$this->table} (chat_type)
                VALUES ('direct')
                RETURNING id
            ");

            $stmt->execute();
            $row = $stmt->fetch();

            if (!$row) {
                $this->pdo->rollBack();
                return false;
            }

            $chatId = $row['id'];

            $participant = $this->pdo->prepare("
                INSERT INTO chat_participants (chat_id, user_id)
                VALUES (:chat_id, :user_id)
            ");

            $participant->execute(['chat_id' => $chatId, 'user_id' => $userA]);
            $participant->execute(['chat_id' => $chatId, 'user_id' => $userB]);

            $this->pdo->commit(); // end transaction

            return $chatId;
        } catch (\Exception $e) {
            $this->pdo->rollBack(); // revert 
            throw $e;
        }
    }

    /* LEFT JOIN LATERAL = last message for preview */
    public function getChatList(string $user_id): array {
        $stmt = $this->pdo->prepare("select 
            c.id, c.chat_type, c.created_at,
            u.id, 
            u.first_name, u.middle_name, u.last_name, u.avatar,
            m.message_content, m.created_at
        from {$this->table} c
        JOIN chat_participants cp ON cp.chat_id = c.id AND cp.user_id = :user_id
        JOIN chat_participants cp2 ON cp2.chat_id = c.id AND cp2.user_id != :user_id
        JOIN users u ON u.id = cp2.user_id
        LEFT JOIN LATERAL (
            select message_content, created_at FROM messages    
            where chat_id = c.id
            ORDER BY created_at DESC
            LIMIT 1 
        ) m ON true
        ORDER BY COALESCE(m.created_at, c.created_at) DESC
        ");

        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}

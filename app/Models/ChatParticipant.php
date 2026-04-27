<?php

namespace App\Models;

class ChatParticipant extends Model {
    protected string $table = "chat_participants";

    public readonly string $chat_id;
    public readonly string $user_id;
    public readonly string $joined_at;

    /** 
     * @param array $row
     */
    private function hydrate(array $row): self {
        $participant             = new self();
        $participant->chat_id    = $row['chat_id'];
        $participant->user_id    = $row['user_id'];
        $participant->joined_at  = $row['joined_at'];

        return $participant;
    }

    public function getDirectChatWith(string $sender, string $recipient): ?array {
        $stmt = $this->pdo->prepare("
            SELECT c.id, c.name, c.chat_type, c.created_at
            FROM chats c
            JOIN chat_participants cp1 ON cp1.chat_id = c.id AND cp1.user_id = :sender
            JOIN chat_participants cp2 ON cp2.chat_id = c.id AND cp2.user_id = :recipient
            WHERE c.chat_type = 'direct'
            LIMIT 1
        ");

        $stmt->execute([
            'sender' => $sender,
            'recipient' => $recipient
        ]);

        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getRecipientId(string $chatId, string $currentUserId): ?string {
        $stmt = $this->pdo->prepare("
            select user_id from {$this->table} 
            where chat_id = :chat_id AND user_id != :user_id
            LIMIT 1
        ");

        $stmt->execute(['chat_id' => $chatId, 'user_id' => $currentUserId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ? $row['user_id'] : null;
    }
}

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

        return participant;
    }

    public function getDirectChatWith(string $sender, string $recipient): void {
        $stmt = $this->pdo->prepare("
            SELECT c.id
            FROM chats c
            JOIN chat_participants cp1 ON cp1.chat_id = c.id AND cp1.user_id = :sender
            JOIN chat_participants cp2 ON cp2.chat_id = c.id AND cp2.user_id = :recipient
        ");  

        $stmt->execute([
            'sender' => $sender,
            'recipient' => $recipient
        ]);
    }
}

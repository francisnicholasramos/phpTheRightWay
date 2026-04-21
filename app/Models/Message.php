<?php

namespace App\Models;

class Message extends Model {
    protected string $table = 'messages';

    public readonly string $id;
    public readonly string $chat_id;
    public readonly string $sender_id;
    public readonly ?string $reply_to_id;
    public readonly string $message_content;
    public readonly string $created_at;

    /** 
     * @param array $row
     */
    private function hydrate(array $row): self {
        $message                    = new self();
        $message->id                = $row['id'];
        $message->chat_id           = $row['chat_id'];
        $message->sender_id         = $row['sender_id'];
        $message->reply_to_id       = $row['reply_to_id'];
        $message->message_content   = $row['message_content'];
        $message->created_at        = $row['created_at'];

        return $message;
    }

    public function createMessage(array $data) {
        $stmt = $this->pdo->prepare("
            insert into {$this->table} 
            (chat_id, sender_id, message_content)
            values (:chat_id, :sender_id, :message_content)
        ");

        return $stmt->execute($data);
    }
}

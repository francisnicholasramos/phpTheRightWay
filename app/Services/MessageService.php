<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatParticipant;
use App\Models\Message;

class MessageService {
    protected Chat $chatModel;
    protected ChatParticipant $chatParticipantModel;
    protected Message $messageModel;

    public function __construct() {
        $this->chatModel = new Chat();
        $this->chatParticipantModel = new ChatParticipant();
        $this->messageModel = new Message();
    }

    public function messageUser(array $data): string|false {
        $senderId = $data['sender_id'];
        $recipientId = $data['recipient_id'];
        $content = $data['message_content'];

        $chat = $this->chatParticipantModel->getDirectChatWith($senderId, $recipientId);

        if ($chat) {
            $chatId = $chat['id'];
        } else {
            $chatId = $this->chatModel->createDirectChat($senderId, $recipientId);
        }

        if (!$chatId) {
            return false;
        }

        return $this->messageModel->createMessage([
            'chat_id' => $chatId,
            'sender_id' => $senderId,
            'message_content' => $content,
        ]);
    }

    public function getMessages(string $chatId): array {
        return $this->messageModel->getMessagesByChatId($chatId);
    }

    public function getRecipientId(string $chatId, string $currentUserId): ?string {
        return $this->chatParticipantModel->getRecipientId($chatId, $currentUserId);
    }
    
    public function findOrCreateChat(string $senderId, string $recipientId): string {
        $chat = $this->chatParticipantModel->getDirectChatWith($senderId, $recipientId);

      if ($chat) {
          return $chat['id'];
      }

      return $this->chatModel->createDirectChat($senderId, $recipientId);
    }
}

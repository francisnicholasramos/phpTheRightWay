<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\MessageService;
use Core\Request;
use Core\Response;

class MessageController {
    public function sendMessageHandler(): void {
        if (!AuthService::check()) {
            (new Response())->redirect('/login');
            return;
        }

        $request = new Request();
        $recipientId = $request->post('recipient_id');
        $content = $request->post('message_content');

        if (empty($recipientId) || empty($content)) {
            $session = new \Core\Session();
            $session->flash('error', 'Recipient and message are required.');

            (new Response())->redirect('/messages');
            return;
        }

        $user = AuthService::user();
        $messageService = new MessageService();
        $messageService->messageUser([
            'sender_id' => $user->id,
            'recipient_id' => $recipientId,
            'message_content' => $content,
        ]);

        (new Response())->redirect('/messages');
    }

    public function showChat(string $chatId): void {
        if (!AuthService::check()) {
            (new Response())->redirect('/login');
            return;
        }

        $currentUserId = AuthService::user()->id;
        $messageService = new MessageService();
        $messages = $messageService->getMessages($chatId);
        $recipientId = $messageService->getRecipientId($chatId, $currentUserId);

        require __DIR__ . '/../../resources/views/components/chatbox.php';
    }

    public function startChatHandler(string $userId): void {
        if (!AuthService::check()) {
            (new Response())->redirect('/login');
            return;
        }

        $currentUserId = AuthService::user()->id;
        $messageService = new MessageService();

        $chat = $messageService->findOrCreateChat($currentUserId, $userId);

        (new Response())->redirect('/messages/' . $chat);
    }
}

<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Services\MessageService;
use Core\Request;
use Core\Response;

class MessageController {
    /* list of chat conversations */
    public function index(): void {
        if (!AuthService::check()) {
            (new Response())->redirect('/login');
            return;
        }

        $userId = AuthService::user()->id;
        $messageService = new MessageService();
        $conversations = $messageService->getUserConversations($userId);

        require __DIR__ . '/../../resources/views/feed/messages.php';
    }

    public function sendMessageHandler(): void {
        if (!AuthService::check()) {
            (new Response())->redirect('/login');
            return;
        }

        $request = new Request();
        $recipientId = $request->post('recipient_id');
        $content = trim($request->post('message_content'));

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

        if ($request->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            return;
        }

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
        $recipient = (new User())->findById($recipientId);

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

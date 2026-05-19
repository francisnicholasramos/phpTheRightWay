<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\ChatParticipant;
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
        $chatId = $messageService->messageUser([
            'sender_id' => $user->id,
            'recipient_id' => $recipientId,
            'message_content' => $content,
        ]);

        if ($request->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'chat_id' => $chatId]);
            return;
        }

        (new Response())->redirect('/messages/' . $chatId);
    }

    public function showChat(string $chatId): void {
        if (!AuthService::check()) {
            (new Response())->redirect('/login');
            return;
        }

        $currentUserId = AuthService::user()->id;

        $chatParticipant = new ChatParticipant();

        $isChatParticipant = $chatParticipant->isParticipant($chatId, $currentUserId);
        if (!$isChatParticipant) {
            http_response_code(403);
            echo "403 Forbidden";
            return;
        }

        $chatParticipant->updateLastRead($chatId, $currentUserId);

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

    public function getUnreadChatHandler(): void {
        $userId = $_SESSION['user_id'];
        if (!$userId) {
            (new Response())->json(['message' => 'Unauthorized'], 401);
            return;
        }

        $count = (new MessageService())->getUnreadChat($userId);
        (new Response())->json(['count' => $count]);
    }
}

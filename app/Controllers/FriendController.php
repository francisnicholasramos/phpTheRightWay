<?php

namespace App\Controllers;

use App\Services\FriendService;
use App\Services\AuthService;
use App\Models\User;
use Core\Request;
use Core\Response;
use Core\View;

class FriendController {
    private FriendService $friendService;

    public function __construct() {
        $this->friendService = new FriendService();
    }

    public function sendFriendRequest(): void {
        if (!AuthService::check()) {
            (new Response())->json(['message' => 'Unauthorized'], 401);
            return;
        }

        $requester_id = AuthService::user()->id;
        $recipient_id = (new Request())->post('recipient_id');

        $result = $this->friendService->sendFriendRequest($requester_id, $recipient_id);

        (new Response())->json([
            'message' => $result, 
            'recipientId' => $recipient_id
        ]);
    }

    public function acceptRequest(): void {
         if (!AuthService::check()) {
            (new Response())->json(['message' => 'Unauthorized'], 401);
            return;
         }

         $recipient_id = AuthService::user()->id;
         $requester_id = (new Request())->post('requester_id');

         $result = $this->friendService->acceptRequest($requester_id, $recipient_id);

         (new Response())->json(['success' => $result]);
     }

    public function declineRequest(): void {
        if (!AuthService::check()) {
            (new Response())->json(['message' => 'Unauthorized'], 401);
            return;
        }

        $recipient_id = AuthService::user()->id;
        $requester_id = (new Request())->post('requester_id');

        $result = $this->friendService->declineRequest($requester_id, $recipient_id);

        (new Response())->json(['success' => $result]);
    }

    public function friendList(string $username): void {
        $user = (new User())->findByUsername($username);
        if (!$user) {
            http_response_code(404);
            echo "User not found";
            return;
        }

        $friends = $this->friendService->getFriends($user->id, 20, 0);

        View::render('components/friend-list', [
            'user' => $user,
            'friends' => $friends
        ]);
    }

    public function loadMoreFriends(string $username): void {
        $offset = (int) ($_GET['offset'] ?? 0);
        $user = (new User())->findByUsername($username);

        if (!$user) {
            (new Response())->json([], 404);
            return;
        }

        $friends = $this->friendService->getFriends($user->id, 20, $offset);

        (new Response())->json($friends);
    }

    public function cancelFriendRequest(): void {
        if (!AuthService::check()) {
            (new Response())->json(['message' => 'Unauthorized'], 401);
            return;
        }

        $requester_id = AuthService::user()->id;
        $recipient_id = (new Request())->post('recipient_id');

        $cancel_req = $this->friendService->cancelFriendRequest($requester_id, $recipient_id);

        (new Response())->json(['message' => $cancel_req]);
    }
}

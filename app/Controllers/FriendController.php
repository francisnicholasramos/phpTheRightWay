<?php

namespace App\Controllers;

use App\Services\FriendService;
use App\Services\AuthService;
use Core\Request;
use Core\Response;

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

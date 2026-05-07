<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Friend;
use App\Models\ChatParticipant;
use App\Services\AuthService;

class ProfileController {
    public function viewProfile(string $username): void {
        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if (!$user) {
            http_response_code(404);
            echo "User not found";
            return;
        }

        $postModel = new Post();
        $posts = $postModel->getByUserId($user->id);

        $existingChatId = null; // check if this user has already convo with current logged-user 
        $isPending = false; // for the user who triggers the event 
        $isIncoming = false; // for user WHO will receive the event (friend request)
        $isFriends = false; // if it's already friends with the other user

        if (AuthService::check()) {
            $currentUser = AuthService::user()->id;
            $chat = (new ChatParticipant())->getDirectChatWith($currentUser, $user->id);
            $existingChatId = $chat ? $chat['id'] : null;
            $isPending = (new Friend())->hasPendingRequest($currentUser, $user->id);
            $isIncoming = (new Friend())->hasIncomingRequest($user->id, $currentUser);
            $isFriends = (new Friend())->isFriends($currentUser, $user->id);
        }

        require_once __DIR__ . '/../../resources/views/components/profile.php';
    }
}

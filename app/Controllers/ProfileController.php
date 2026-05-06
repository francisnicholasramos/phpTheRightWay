<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\User;
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

        // check if this user has already convo with current logged-user
        $existingChatId = null;
        if (AuthService::check()) {
            $currentUser = AuthService::user()->id;
            $chat = (new ChatParticipant())->getDirectChatWith($currentUser, $user->id);
            $existingChatId = $chat ? $chat['id'] : null;
        }

        require_once __DIR__ . '/../../resources/views/components/profile.php';
    }
}

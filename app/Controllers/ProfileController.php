<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Services\AuthService;
use Core\Request;
use Core\Response;

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

        require_once __DIR__ . '/../../resources/views/components/profile.php';
    }
}

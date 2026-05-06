<?php

namespace App\Controllers;

use App\Services\LikeService;
use App\Services\AuthService;
use Core\Request;
use Core\Response;

class LikeController {
    private LikeService $likeService;

    public function __construct() {
        $this->likeService = new LikeService();
    }

    public function likePostHandler(): void {
        $request = new Request();

        if (!AuthService::check()) {
            (new Response())->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $entity_id = $request->post('post_id');
        $userId = AuthService::user()->id;

        $result = $this->likeService->likePost($entity_id, 'post', $userId);

        (new Response())->json($result);
    }
}

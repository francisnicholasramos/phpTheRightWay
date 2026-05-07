<?php

namespace App\Controllers;

use App\Services\PokeService;
use App\Services\AuthService;
use Core\Request;
use Core\Response;

class PokeController {
    private PokeService $pokeService;

    public function __construct() {
        $this->pokeService = new PokeService();
    }

    public function pokeHandler(): void {
        if (!AuthService::check()) {
            (new Response())->json(['message' => 'Unauthorized'], 401);
            return;
        }

        $from_user_id = AuthService::user()->id;
        $to_user_id = (new Request())->post('to_user_id');

        $result = $this->pokeService->poke($from_user_id, $to_user_id);

        (new Response())->json(['success' => $result, 'recipientId' => $to_user_id]);
    }
}

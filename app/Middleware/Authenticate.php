<?php

namespace App\Middleware;

use App\Services\AuthService;
use Core\Response;

class Authenticate {
    public function handle(): void {
        if (!AuthService::check()) {
            (new Response())->redirect('/login');
        }
    }
}

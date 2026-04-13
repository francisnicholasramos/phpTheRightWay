<?php 

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\PostService;
use Core\Request;
use Core\Response;

class PostController {
    public function createPostHandler(): void {

        if (!AuthService::check()) {
            (new Response())->redirect('/login');
            return;
        }

        $request = new Request();
        $content = $request->post('content');
        $audience = $request->post('audience');

        // input validation
        if (empty($content)) {
            $session = new \Core\Session();
            $session->flash('error' , 'You must write something.');

            (new Response())->redirect('/feed');
            return;
        }

        $user = AuthService::user();
        $postService = new PostService();
        $postService->post($user->id, $content, $audience);

        (new Response())->redirect('/feed');
    }
}

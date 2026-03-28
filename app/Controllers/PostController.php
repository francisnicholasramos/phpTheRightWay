<?php 

namespace App\Controllers;

use App\Services\AuthService;
use App\Models\Post;
use Core\Request;
use Core\Response;

class PostController {
    public function createPostHandler(): void {

        if (!AuthService::check()) {
            (new Response())->redirect('/login');
            return;
        }

        $user = AuthService::user();

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

        $post = new Post();
        $post->createPost([
            'user_id' => $user->id,
            'content' => $content,
            'visibility' => $audience
        ]);

        (new Response())->redirect('/feed');
    }
}

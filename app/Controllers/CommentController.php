<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Models\Comment;
use Core\Request;
use Core\Response;

class CommentController {
    public function postCommentHandler(): void {
        if (!AuthService::check()) {
            (new Response())->redirect('/login');
            return;
        }

        $user = AuthService::user();

        $request = new Request();
        $data = $request->post('comment');
        $postId = $request->post('post_id');

        if (empty($data)) {
            $session = new \Core\Session();
            $session->flash('error' , 'You must write something.');

            (new Response())->redirect('/feed');
            return;
        }

        $comment = new Comment();
        $comment->createComment([
            'user_id' => $user->id,
            'post_id' => $postId,
            'content' => $data
        ]);
    }
}

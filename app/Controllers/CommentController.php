<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\CommentService;
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
            (new Response())->json(['error' => 'You must write something.'], 422);
            return;
        }

        $comment = new CommentService();
        $result = $comment->postComment(
            $user->id,
            $postId,
            $data
        );

        (new Response())->json([
            'success' => true,
            'comment' => $result['comment'],
            'recipientId' => $result['recipientId']
        ]);
    }
}

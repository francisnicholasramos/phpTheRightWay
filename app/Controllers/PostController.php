<?php 

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\PostService;
use Core\Request;
use Core\Response;
use Core\View;

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

    public function viewPost(string $postId): void {
        if (!AuthService::check()) {
            (new Response())->redirect('/login');
            return;
        }

        $user = AuthService::user();

        $postModel = new \App\Models\Post();
        $post = $postModel->getPostById($postId, $user->id);

        if (!$post) {
            http_response_code(404);
            echo "Post not found.";
            return;
        }

        $commentModel = new \App\Models\Comment();
        $comments = $commentModel->getPostById($postId);

        View::render('components/post-view', [ 
            'user' => $user,
            'post' => $post,
            'comments' => $comments
        ]);
    }

    public function editPostHandler(): void {
        if (!AuthService::check()) {
            (new Response())->json(['message' => 'Forbidden']);
            return;
        }

        $request = new Request();
        $postId  = $request->post('post_id');
        $content = $request->post('edit-content');

        if (empty($postId)) {
            (new Response())->json(['message' => 'Something went wrong.']);
            return;
        }

        if (empty($content)) {
            (new Response())->json(['message' => 'You must write something.'], 422);
            return;
        }

        $data          = new \App\Dto\EditPostDto();
        $data->id      = $postId;
        $data->content = $content;

        $user    = AuthService::user();
        $success = (new PostService())->editPost($user->id, $data);

        if (!$success) {
            (new Response())->json(['message' => 'Forbidden'], 403);
            return;
        }

        (new Response())->redirect('/feed');
    }
}

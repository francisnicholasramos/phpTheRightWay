<?php

namespace App\Controllers;

use Core\Response;
use Core\View;
use App\Models\Post;

class FeedController {
    public function index(): void {
        $post = new Post();
        $posts = $post->getAllPosts(
            $_SESSION['user_id'] ?? null,
            limit: 10,
            offset: 0
        );

        View::render('feed/index', ['posts' => $posts]);
    }

    public function loadMore(): void {
        $offset = (int) ($_GET['offset'] ?? 0);
        $post = new Post();
        $posts = $post->getAllPosts($_SESSION['user_id'] ?? null, 10, $offset);

        ob_start();
        foreach ($posts as $post) {
            require __DIR__ . '/../../resources/views/components/post-card.php';
        }

        $html = ob_get_clean();

        (new Response())->json([ 
            'html' => $html,
            'hasMore' => count($posts) === 10
        ]);
    }
}

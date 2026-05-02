<?php

namespace App\Controllers;

use Core\View;
use App\Models\Post;

class FeedController {
    public function index(): void {
        $post = new Post();
        $posts = $post->getAllPosts($_SESSION['user_id'] ?? null);

        View::render('feed/index', ['posts' => $posts]);
    }
}

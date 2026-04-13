<?php 

namespace App\Services;

use App\Models\Post;

class PostService {
    private Post $postModel;

    public function __construct() {
        $this->postModel = new Post();
    }

    /** 
     * @return bool
     */
    public function post(string $user_id, string $content, string $visibility): bool {
        return $this->postModel->createPost([ 
            'user_id' => $user_id, 
            'content' => $content, 
            'visibility' => $visibility
        ]);
    }

}


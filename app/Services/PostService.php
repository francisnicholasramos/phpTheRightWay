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

    /**
     * @return bool
     */
    public function editPost(string $currentUser, \App\Dto\EditPostDto $data): bool {
        $user = $this->postModel->getOwnerId($data->id);

        if (!$user || $user->user_id !== $currentUser) return false;

        return $this->postModel->updatePost($data);
    }

}


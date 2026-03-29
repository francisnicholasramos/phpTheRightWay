<?php

namespace App\Services;

use App\Models\Likes;

class LikeService {
    private Likes $likesModel;

    public function __construct() {
        $this->likesModel = new Likes();
    }

    /** 
     * @return array<string, mixed>
     */
    public function like(string $post_id, string $user_id): array {
        $isLiked = $this->likesModel->toggleLike($post_id, $user_id);

        $count = $this->likesModel->getLikesCount($post_id);

        return [
            'liked' => !$isLiked,
            'count' => $count
        ];
    }
}

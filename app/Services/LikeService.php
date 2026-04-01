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
    public function like(string $entity_id, string $entity_type, string $user_id): array {
        $isLiked = $this->likesModel->toggleLike($entity_id, $entity_type, $user_id);

        $count = $this->likesModel->getLikesCount($entity_id, $entity_type);

        return [
            'liked' => !$isLiked,
            'count' => $count
        ];
    }
}

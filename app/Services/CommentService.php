<?php

namespace App\Services;

use App\Models\Likes;
use App\Models\Comment;

class CommentService {
    private Likes $likesModel;
    private Comment $commentModel;

    public function __construct() {
        $this->likesModel = new Likes();
        $this->commentModel = new Comment();
    }

    /**
     * @return array<string, mixed>
     */
    public function postComment(string $entity_id, string $entity_type, string $user_id): array {
        $comment = $this->commentModel->createComment($entity_id, $entity_type, $user_id);

        $commentLikes = $this->likesModel->getLikesCount($entity_id, $entity_type);

        return [
            'comment' => $comment,
            'commentLikes' => $commentLikes
        ];
    }

}

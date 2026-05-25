<?php

namespace App\Services;

use App\Models\Likes;
use App\Models\Comment;

class CommentService {
    private Likes $likesModel;
    private Comment $commentModel;
    private NotificationService $notificationService;

    public function __construct() {
        $this->likesModel = new Likes();
        $this->commentModel = new Comment();
        $this->notificationService = new NotificationService();
    }

    /**
     * @return array<string, mixed>
     */
    public function postComment(
        string $userId,
        string $postId,
        string $content,
    ): array {
        $postModel = new \App\Models\Post();
        $post = $postModel->getOwnerId($postId);
        $receiver = $post->user_id;
        
        $comment = $this->commentModel->createComment([ 
            'user_id' => $userId,
            'post_id' => $postId,
            'content' => $content
        ]);

        $recipientId = null;

        if ($receiver !== $userId) {
            $this->notificationService->storeNotification(
                $receiver,
                $userId,
                $postId,
                'comment'
            );
            $recipientId = $receiver;
        }

        return [
            'comment' => $comment,
            'recipientId' => $recipientId
        ];
    }

}

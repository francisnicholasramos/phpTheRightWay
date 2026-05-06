<?php

namespace App\Services;

use App\Models\Likes;
use App\Models\Post;
use App\Services\NotificationService;

class LikeService {
    private Likes $likesModel;
    private NotificationService $notificationService;

    public function __construct() {
        $this->likesModel = new Likes();
        $this->notificationService = new NotificationService();
    }

    /** 
     * @return array<string, mixed>
     */
    public function likePost(
        string $entity_id, 
        string $entity_type, 
        string $from_user_id, 
    ): array {
        $postModel = new Post();
        $post = $postModel->getOwnerId($entity_id);
        $postOwnerId = $post->user_id;

        $isLiked = $this->likesModel->toggleLike($entity_id, $entity_type, $from_user_id);

        // don't notify unlikes
        if ($isLiked) {
            // don't notify if user liking his own posts
            if ($postOwnerId !== $from_user_id) {
                $notification = $this->notificationService->storeNotification(
                    $postOwnerId, 
                    $from_user_id, 
                    $entity_id, 
                    'like'
                );
            } 
        } else {
            // unlike = delete notification
            if ($postOwnerId !== $from_user_id) {
                $this->notificationService->removeNotification(
                    $postOwnerId, 
                    $from_user_id, 
                    $entity_id, 
                    'like'
                );
            }
        }

        $count = $this->likesModel->getLikesCount($entity_id, $entity_type);

        return [
            'liked' => $isLiked,
            'count' => $count,
            'recipientId' => ($isLiked && $postOwnerId !== $from_user_id) ? $postOwnerId : null, // return only for likes not including unlikes
        ];
    }
}

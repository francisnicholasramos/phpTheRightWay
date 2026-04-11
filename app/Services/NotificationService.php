<?php

namespace App\Services;

use App\Models\Notifications;

class NotificationService {
    private Notifications $notificationModel;

    public function __construct() {
        $this->notificationModel = new Notifications();
    }

    /**
     * @return array<string, mixed> 
     */
    public function likeNotify(
        string $user_id,
        string $from_user_id,
        string $entity_id, 
        string $entity_type
    ): array {
        $like = $this->notificationModel->createNotification(
            $user_id, $from_user_id, $entity_id, $entity_type
        );

        return [
            'results' => $like,
            'user_id' => $user_id,
            'from_user_id' => $from_user_id,
            'entity_id' => $entity_id,
            'entity_type' => $entity_type
        ];
    }


}

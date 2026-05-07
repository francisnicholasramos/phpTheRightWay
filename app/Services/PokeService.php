<?php

namespace App\Services;

use App\Models\Notifications;
use App\Services\NotificationService;

class PokeService {
    private Notifications $notificationModel;
    private NotificationService $notificationService;

    public function __construct() {
        $this->notificationModel = new Notifications();
        $this->notificationService = new NotificationService();
    }

    public function poke(string $from_user_id, string $to_user_id): bool {
        $alreadyPoked = $this->notificationModel->pokeNotification($to_user_id, $from_user_id);

        if ($alreadyPoked) {
            return false;
        }

        $this->notificationService->storeNotification(
            $to_user_id,
            $from_user_id,
            $from_user_id,
            'poke'
        );

        return true;
    }
}

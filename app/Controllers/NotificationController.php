<?php

namespace App\Controllers;

use App\Models\Notifications;
use Core\Response;

class NotificationController {
    private Notifications $notificationModel;

    public function __construct() {
        $this->notificationModel = new Notifications();
    }

    public function countHandler(): void {
        $user_id = $_SESSION['user_id'] ?? null;

        if (!$user_id) {
            (new Response())->json(['message' => 'Unauthorized'], 401);
            return;
        }

        $notifications = $this->notificationModel->getNotification($user_id);
        $count = count($notifications);

        (new Response())->json(['count' => $count]);
    }
}

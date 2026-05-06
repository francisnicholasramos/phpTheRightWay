<?php

namespace App\Controllers;

use App\Models\Notifications;
use Core\Response;
use App\Services\AuthService;
use Core\View;

class NotificationController {
    private Notifications $notificationModel;

    public function __construct() {
        $this->notificationModel = new Notifications();
    }

    public function index(): void {
        if (!AuthService::check()) {
            (new Response())->redirect('/login');
            return;
        }

        $user_id = $_SESSION['user_id'];

        $notifications = $this->notificationModel->getNotification($user_id);

        // mark as all read when visited
        $this->notificationModel->markAllRead($user_id);

        View::render('/components/notification', ['notifications' => $notifications]);
    }

    public function countHandler(): void {
        $user_id = $_SESSION['user_id'];

        if (!$user_id) {
            (new Response())->json(['message' => 'Unauthorized'], 401);
            return;
        }

        $count = $this->notificationModel->getUnreadNotif($user_id);

        (new Response())->json(['count' => $count]);
    }
}

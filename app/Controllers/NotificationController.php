<?php

namespace App\Controllers;

use App\Services\NotificationService;
use Core\Request;
use Core\Session;

class NotificationController {
    private NotificationService $notificationService;

    public function handleLikeNotify(): void {
        $request = new Request();
        $session = new Session();

        $from_user_id = $session->get('user_id');
        $user_id = $request->post('user_id');
        $entity_id = $request->post('post_id');
        
        // don't notify, if it's own like
        if ($from_user_id === $user_id) {
            return; 
        }

        $result = $this->notificationService->likeNotify(
            $user_id, 
            $from_user_id, 
            $entity_id, 
            'like'
        );
    }
}

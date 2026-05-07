<?php

namespace App\Services;

use App\Models\Friend;
use App\Services\NotificationService;

class FriendService {
    private Friend $friendModel;
    private NotificationService $notificationService;

    public function __construct() {
        $this->friendModel = new Friend();
        $this->notificationService = new NotificationService();
    }

    public function sendFriendRequest(string $requester_id, string $recipientId): bool {
        $result = $this->friendModel->createFriendRequest($requester_id, $recipientId);

        if ($result) {
            $this->notificationService->storeNotification(
                $recipientId,
                $requester_id,
                $requester_id, // entity. it could be like, comment or event friend req
                'friend_request'
            );
        }

        return $result;
    }

    public function acceptRequest(string $requester_id, string $recipient_id): bool {
        $result = $this->friendModel->acceptRequest($requester_id, $recipient_id);
        
        if ($result) {
            $this->notificationService->removeNotification(
                $recipient_id,
                $requester_id,
                $requester_id,
                'friend_request'
            );
        }

        return $result;
    }

    public function declineRequest(string $requester_id, string $recipient_id): bool {
        $result = $this->friendModel->deleteFriendRequest($requester_id, $recipient_id);

        if ($result) {
            $this->notificationService->removeNotification(
                $recipient_id,
                $requester_id,
                $requester_id,
                'friend_request'
            );
        }

        return $result;
    }
    
    public function cancelFriendRequest(string $requester_id, string $recipientId): bool {
        $result = $this->friendModel->deleteFriendRequest($requester_id, $recipientId);

        if ($result) {
            $this->notificationService->removeNotification(
                $recipientId,
                $requester_id,
                $requester_id,
                'friend_request'
            );
        }

        return $result;
    }
}

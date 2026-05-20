<?php

namespace App\Models;

class UserPhoto extends Model {
    protected string $table = 'user_photos';

    public function insertPhoto(
        string $userId, 
        string $url, 
        string $type,
        ?string $postId=null
    ): bool {
        $stmt = $this->pdo->prepare("
            insert into {$this->table} (user_id, url, type, post_id) 
            values (:user_id, :url, :type, :post_id)
        ");

        return $stmt->execute([
            ':user_id' => $userId, 
            ':url' => $url, 
            ':type' => $type,
            ':post_id' => $postId
        ]);
    }
}

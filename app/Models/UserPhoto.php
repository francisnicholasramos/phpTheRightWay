<?php

namespace App\Models;

class UserPhoto extends Model {
    protected string $table = 'user_photos';

    public function getByUserId(string $userId): array {
        $stmt = $this->pdo->prepare("
            SELECT id, url, type, post_id, created_at
            FROM {$this->table}
            WHERE user_id = :user_id
            ORDER BY created_at DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

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

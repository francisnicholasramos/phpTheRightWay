<?php 

namespace App\Models;

class UserTimestamp extends Model {
    protected string $table = 'user_timestamp';


    public function getNameChangedAt(string $userId): ?string {
        $stmt = $this->pdo->prepare("
          SELECT name_changed_at FROM {$this->table} WHERE user_id = :user_id
        ");
        $stmt->execute([':user_id' => $userId]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row['name_changed_at'] ?? null;
    }

    public function upsertNameChanged(string $userId): bool {
        $stmt = $this->pdo->prepare("
             INSERT INTO {$this->table} (user_id, name_changed_at)
             VALUES (:user_id, NOW())
             ON CONFLICT (user_id) DO UPDATE SET name_changed_at = NOW()
        ");

        return $stmt->execute([':user_id' => $userId]);
    }
}

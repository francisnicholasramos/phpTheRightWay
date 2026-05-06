<?php 

namespace App\Models;

class Notifications extends Model {
    protected string $table = 'notifications';

    public readonly string $id;
    public readonly string $user_id; // owner
    public readonly string $from_user_id; // who likes
    public readonly string $entity_id; // entities: like, comment, poke, etc..
    public readonly string $entity_type;
    public readonly string $is_read;
    public readonly string $created_at;

    /**
     * @param array $row
     */
    private function hydrate(array $row): self {
        $notification           = new self();
        $notification->id            = $row['id'];
        $notification->user_id       = $row['user_id'];
        $notification->from_user_id  = $row['from_user_id'];
        $notification->entity_id     = $row['entity_id'];
        $notification->entity_type   = $row['entity_type'];
        $notification->is_read       = $row['is_read'];
        $notification->created_at    = $row['created_at'];

        return $notification;
    }

    /**
     * @param array $data
     */
    public function createNotification(array $data): bool {
        $stmt = $this->pdo->prepare("insert into {$this->table}
            (user_id, from_user_id, entity_id, entity_type)
            values (:user_id, :from_user_id, :entity_id, :entity_type)
        ");
        return $stmt->execute($data);
    }

    /** 
     * @param array $data
     */
    public function deleteNotification(array $data): bool {
        $stmt = $this->pdo->prepare("
            DELETE from {$this->table} 
            WHERE user_id = :user_id
            AND from_user_id = :from_user_id 
            AND entity_id = :entity_id
            AND entity_type = :entity_type
        ");

        return $stmt->execute($data);
    }
    
    public function getNotification(string $user_id) {
        $stmt = $this->pdo->prepare("
            SELECT
                n.id,
                n.entity_type,
                n.entity_id,
                n.is_read,
                n.created_at,

                sender.first_name,
                sender.middle_name,
                sender.last_name,
                sender.avatar
            FROM {$this->table} n
            JOIN users sender ON sender.id = n.from_user_id
            WHERE n.user_id = :user_id
            ORDER BY n.created_at DESC
        ");

        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getUnreadNotif(string $user_id): int {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM {$this->table}
            WHERE user_id = :user_id AND is_read = FALSE
        ");
        $stmt->execute(['user_id' => $user_id]);
        return (int) $stmt->fetchColumn();
    }

    public function markAllRead(string $user_id): void {
        $stmt = $this->pdo->prepare("
            UPDATE {$this->table} SET is_read = TRUE
            WHERE user_id = :user_id AND is_read = FALSE
        ");
        $stmt->execute(['user_id' => $user_id]);
    }
}

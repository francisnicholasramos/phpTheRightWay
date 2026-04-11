<?php 

namespace App\Models;

class Notifications extends Model {
    protected string $table = 'notifications';

    public readonly string $id;
    public readonly string $user_id; // owner
    public readonly string $from_user_id; // who likes
    public readonly string $entity_id; // activities: like, comment, poke, etc..
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
        $stmt = $this->pdo->prepare("insert into ${this->table}
            (user_id, from_user_id, entity_id, entity_type)
            values (:user_id, :from_user_id, :entity_id, :entity_type)
        ");
        return $stmt->execute($data);
    }
}

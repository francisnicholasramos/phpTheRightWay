<?php

namespace App\Models;

class Likes extends Model {
    protected string $table = 'likes';

    public readonly string $user_id;
    public readonly string $entity_id;
    public readonly string $entity_type;

   /**
    * @param array $row
    */ 
    public function hydrate(array $row): self {
        $likes            = new self();
        $likes->user_id   = $row['user_id'];
        $likes->entity_id   = $row['entity_id'];
        $likes->entity_type   = $row['entity_type'];

        return $likes;
    }

    public function toggleLike(string $entity_id, string $entity_type, string $user_id): bool {
        $stmt = $this->pdo->prepare("
            select * from {$this->table}
            where entity_id = :entity_id 
            AND entity_type = :entity_type
            AND user_id = :user_id
        ");

        $stmt->execute([
            ':entity_id' => $entity_id,
            ':entity_type' => $entity_type,
            ':user_id' => $user_id
        ]);

        $isLiked = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($isLiked) {
            $unlike = $this->pdo->prepare("
                delete from {$this->table}
                where entity_id = :entity_id 
                AND entity_type = :entity_type
                AND user_id = :user_id
            ");

            $unlike->execute([
                ':entity_id' => $entity_id, 
                ':entity_type' => $entity_type, 
                ':user_id' => $user_id
            ]);
            return false;
        } else {
            $like = $this->pdo->prepare(
                "insert into {$this->table}
                (entity_id, entity_type, user_id)
                values (:entity_id, :entity_type, :user_id)
            ");
            $like->execute([
                ':entity_id' => $entity_id, 
                ':entity_type' => $entity_type, 
                ':user_id' => $user_id
            ]);
            return true;
        }
    }

    public function getLikesCount(string $entity_id, string $entity_type): int {
        $stmt = $this->pdo->prepare(
            "select count(*) from {$this->table} 
            where entity_id  = :entity_id
            and entity_type = :entity_type
        ");
        $stmt->execute([
            ':entity_id' => $entity_id,
            ':entity_type' => $entity_type
        ]);
        
        return (int) $stmt->fetchColumn();
    }

    // total likes
}

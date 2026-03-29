<?php

namespace App\Models;

class Likes extends Model {
    protected string $table = 'likes';

    public readonly string $id;
    public readonly string $user_id;
    public readonly string $post_id;

   /**
    * @param array $row
    */ 
    public function hydrate(array $row): self {
        $likes            = new self();
        $likes->id        = $row['id'];
        $likes->user_id   = $row['user_id'];
        $likes->post_id   = $row['post_id'];

        return $likes;
    }

    public function toggleLike(string $post_id, string $user_id): bool {
        $stmt = $this->pdo->prepare("select * from {$this->table}
            where post_id = :post_id AND user_id = :user_id");

        $stmt->execute([':post_id' => $post_id, ':user_id' => $user_id]);
        $isLiked = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($isLiked) {
            $unlike = $this->pdo->prepare("delete from {$this->table}
                where post_id = :post_id AND user_id = :user_id");
            return $unlike->execute([':post_id' => $post_id, ':user_id' => $user_id]);
        } else {
            $like = $this->pdo->prepare("insert into {$this->table}
                (post_id, user_id)
                values (:post_id, :user_id)");
            return $like->execute([':post_id' => $post_id, ':user_id' => $user_id]);
        }
    }

    public function getLikesCount(string $post_id): int {
        $stmt = $this->pdo->prepare("select count(*) from {$this->table} 
            where post_id = :post_id");
        $stmt->execute([':post_id' => $post_id]);
        
        return (int) $stmt->fetchColumn();
    }

    // total likes
}

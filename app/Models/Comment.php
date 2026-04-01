<?php 

namespace App\Models;

class Comment extends Model {
    protected string $table = 'comments';

    public readonly string $id;
    public readonly string $content;
    public readonly string $user_id;
    public readonly string $post_id;
    public readonly ?string $parent_id; // for replies
    public readonly string $created_at;
    public readonly int $likes_count;

    /**
     * @param array $row
     */
    private function hydrate(array $row): self {
        $comment                = new self();
        $comment->id            = $row['id'];
        $comment->content       = $row['content'];
        $comment->user_id       = $row['user_id'];
        $comment->post_id       = $row['post_id'];
        $comment->parent_id     = $row['parent_id'];
        $comment->created_at    = $row['created_at'];
        $comment->likes_count   = (int) ($row['likes_count'] ?? '');

        return $comment;
    }

    /**
     * @param array $data
     */
    public function createComment(array $data): bool {
        $stmt = $this->pdo->prepare("insert into {$this->table} 
            (user_id, post_id, content)
            values (:user_id, :post_id, :content)");
        return $stmt->execute($data);
    }
} 

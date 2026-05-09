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

    /* for commenters */
    public readonly ?string $avatar;
    public readonly string $first_name;
    public readonly ?string $middle_name;
    public readonly string $last_name;
    

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

        $comment->avatar        = $row['avatar'] ?? null;
        $comment->first_name    = $row['first_name'];
        $comment->middle_name   = $row['middle_name'] ?? null;
        $comment->last_name     = $row['last_name']; 

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

    public function getPostById(string $postId): array {
        $stmt = $this->pdo->prepare("
            select
                comments.id,
                comments.content,
                comments.user_id,
                comments.post_id,
                comments.parent_id,
                comments.created_at,
                u.avatar,
                u.first_name,
                u.middle_name,
                u.last_name
            FROM {$this->table}
            JOIN users u ON comments.user_id = u.id
            WHERE comments.post_id = :post_id
            ORDER BY comments.created_at ASC
        ");

        $stmt->execute(['post_id' => $postId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $comments = [];
        foreach ($rows as $row) {
            $comments[] = $this->hydrate($row);
        }
        return $comments;
    }
} 

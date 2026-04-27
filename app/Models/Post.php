<?php 

namespace App\Models;

class Post extends Model {
    protected string $table = 'posts';

    public readonly string $id;
    public readonly string $user_id;
    public readonly string $content;
    public readonly string $created_at;
    public readonly string $visibility;
    public readonly string $likes_count;

    /**
     * @param array $row
     */
    private function hydrate(array $row): self {
        $post                = new self();
        $post->id            = $row['id'];
        $post->user_id       = $row['user_id'];
        $post->content       = $row['content'];
        $post->created_at    = $row['created_at'];
        $post->visibility    = $row['visibility'];
        $post->likes_count   = ($row['likes_count'] ?? '');

        return $post;
    }

    /** 
     * @return Post[]
     * */
    public function getAllPosts(): array {
        $stmt = $this->pdo->query("
            select 
                posts.id, 
                posts.user_id, 
                posts.content, 
                posts.created_at, 
                posts.visibility,
                count(likes.user_id) as likes_count
            from {$this->table}
            LEFT JOIN likes
            ON posts.id = likes.entity_id AND likes.entity_type = 'post'
            group by posts.id");  
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $posts = [];
        foreach ($rows as $row) {
            $posts[] = $this->hydrate($row);
        }
        return $posts;
    }

    /** 
     * @param array{user_id: string, content: string, visibility: string} $data
     * @return bool
     */
    public function createPost(array $data): bool {
        $stmt = $this->pdo->prepare("insert into {$this->table}
            (user_id, content, visibility)
            values (:user_id, :content, :visibility)");
        return $stmt->execute($data);
    }

    public function getOwnerId(string $id): ?self {
        $stmt = $this->pdo->prepare("select * from {$this->table} where id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function getByUserId(string $userId): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                p.id, 
                p.user_id, 
                p.content, 
                p.created_at, 
                p.visibility,
                COUNT(l.user_id) AS likes_count
            FROM {$this->table} p
            LEFT JOIN likes l ON p.id = l.entity_id AND l.entity_type = 'post'
            WHERE p.user_id = :user_id
            GROUP BY p.id
            ORDER BY p.created_at DESC
        ");

        $stmt->execute(['user_id' => $userId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $posts = [];
        foreach ($rows as $row) {
            $posts[] = $this->hydrate($row);
        }
        return $posts;
    }
}

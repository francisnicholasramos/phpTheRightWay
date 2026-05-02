<?php 

namespace App\Models;

use App\Dto\PostDto;

class Post extends Model {
    protected string $table = 'posts';

    public readonly string $id;
    public readonly string $user_id;
    public readonly string $content;
    public readonly string $created_at;
    public readonly string $visibility;
    public readonly string $likes_count;
    public readonly bool $liked_by_me;
    public readonly ?string $avatar;
    public readonly string $first_name;
    public readonly ?string $middle_name;
    public readonly string $last_name;

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
        $post->liked_by_me   = (bool) ($row['liked_by_me'] ?? false);
        $post->avatar        = $row['avatar'] ?? null;
        $post->first_name    = $row['first_name'];
        $post->middle_name   = $row['middle_name'] ?? null;
        $post->last_name     = $row['last_name'];

        return $post;
    }

    /**
     * @return Post[]
     * */
    public function getAllPosts(?string $currentUserId = null): array {
        $likedByMeExpr = $currentUserId
            ? 'MAX(CASE WHEN likes.user_id = :current_user_id THEN 1 ELSE 0 END) as liked_by_me'
            : '0 as liked_by_me';

        $sql = "
            select
                posts.id,
                posts.user_id,
                posts.content,
                posts.created_at,
                posts.visibility,
                u.avatar,
                u.first_name,
                u.middle_name,
                u.last_name,
                count(likes.user_id) as likes_count,
                {$likedByMeExpr}
            from {$this->table}
            JOIN users u ON posts.user_id = u.id
            LEFT JOIN likes ON posts.id = likes.entity_id AND likes.entity_type = 'post'
            GROUP BY posts.id, u.avatar, u.first_name, u.middle_name, u.last_name
            ORDER BY posts.created_at DESC";

        if ($currentUserId) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':current_user_id' => $currentUserId]);
        } else {
            $stmt = $this->pdo->query($sql);
        }

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

    public function getOwnerId(string $id): ?PostDto {
        $stmt = $this->pdo->prepare("select id, user_id from {$this->table} where id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) return null;

        $dto                = new PostDto();
        $dto->id            = $row['id'];
        $dto->user_id       = $row['user_id'];

        return $dto;
    }

    public function getByUserId(string $userId): array {
        $stmt = $this->pdo->prepare("
            SELECT
                p.id,
                p.user_id,
                p.content,
                p.created_at,
                p.visibility,
                u.avatar,
                u.first_name,
                u.middle_name,
                u.last_name,
                COUNT(l.user_id) AS likes_count
            FROM {$this->table} p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN likes l ON p.id = l.entity_id AND l.entity_type = 'post'
            WHERE p.user_id = :user_id
            GROUP BY p.id, u.avatar, u.first_name, u.middle_name, u.last_name
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

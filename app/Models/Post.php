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
                count(likes.id) as likes_count
            from {$this->table}
            left join likes
            on posts.id = likes.post_id 
            group by posts.id");  // count likes per post (each post has its own count)
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
}

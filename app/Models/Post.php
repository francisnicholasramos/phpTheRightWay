<?php 

namespace App\Models;

class Post extends Model {
    protected string $table = 'posts';

    public readonly string $id;
    public readonly string $user_id;
    public readonly string $content;
    public readonly string $created_at;
    public readonly string $visibility;

    /**
     * @param array $row
     */
    private function hydrate(array $row): self {
        $this->id            = $row['id'];
        $this->user_id       = $row['user_id'];
        $this->content       = $row['content'];
        $this->created_at    = $row['created_at'];
        $this->visibility    = $row['visibility'];

        return $this;
    }

    /** 
     * @return Post[]
     * */
    public function getAllPosts(): array {
        $stmt = $this->pdo->query("select * from {$this->table}"); 
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $posts = [];
        foreach ($rows as $row) {
            $posts[] = $this->hydrate($row);
        }
        return $posts;
    }
}

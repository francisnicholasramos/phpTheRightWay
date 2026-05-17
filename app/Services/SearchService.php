<?php

namespace App\Services;

use App\Models\User;
use App\Models\Post;

class SearchService {
    protected User $userModel;
    protected Post $postModel;

    public function __construct() {
        $this->userModel = new User();
        $this->postModel = new Post();
    }

    /**
     * @return self[]
     */
    public function searchParam(string $query, ?string $currentUserId=null): array {
        return [
            'users' => $this->userModel->search($query),
            'posts' => $this->postModel->search($query, $currentUserId)
        ];
    }
}

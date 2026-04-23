<?php

namespace App\Services;

use App\Models\User;

class SearchService {
    protected User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function search(string $query): array {
        return [
            'users' => $this->userModel->search($query)
        ];
    }
}

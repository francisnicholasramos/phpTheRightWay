<?php

namespace App\Services;

use App\Models\User;

class SearchService {
    protected User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * @return self[]
     */
    public function searchParam(string $query): array {
        return [
            'users' => $this->userModel->search($query)
        ];
    }
}

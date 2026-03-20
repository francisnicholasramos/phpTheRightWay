<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository {
    private User $model;

    public function __construct() {
        $this->model = new User();
    }

    public function findById(string $id): array|false {
        $stmt = $this->model->getConnection()->prepare(
            "select * from users where id = :id"
        );

        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function findByEmail(string $email): array|false {
        $stmt = $this->model->getConnection()->prepare(
            "select * from users where email = :email"
        );

        $stmt->execute([':email' => $email]);

        return $stmt->fetch();
    }
}

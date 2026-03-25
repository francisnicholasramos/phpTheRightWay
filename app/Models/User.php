<?php

namespace App\Models;

class User extends Model {
    protected string $table = 'users';

    public readonly string $id;
    public readonly string $username;
    public readonly string $email;
    public readonly string $password;
    public readonly ?string $avatar;
    public readonly ?string $bio;

    /**
    * @param array<string, mixed> $row
    */
    private function hydrate(array $row): self {
        $user                = new self();
        $user->id            = $row['id'];
        $user->username      = $row['username'];
        $user->email         = $row['email'];
        $user->password      = $row['password'];
        $user->avatar        = $row['avatar'] ?? null;
        $user->bio           = $row['bio'] ?? null;

        return $user;
    }

    /** 
    * @param array $data
    * */
    public function createUser(array $data): bool {
        $stmt = $this->pdo->prepare(
            "insert into {$this->table} (email, username, password) 
             values (:email, :username, :password)");
        return $stmt->execute($data);
    }

    public function findById(string $id): ?self {
        $stmt = $this->pdo->prepare(
            "select * from {$this->table} where id = :id"
        );

        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC); // PDO's raw output

        return $row ? $this->hydrate($row) : null;
    }

    public function findByEmail(string $email): ?self {
        $stmt = $this->pdo->prepare(
            "select * from {$this->table} where email = :email"
        );

        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

}


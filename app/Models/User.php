<?php

namespace App\Models;

use App\Dto\UserSearchResult;

class User extends Model {
    protected string $table = 'users';

    public readonly string $id;
    public readonly string $first_name;
    public readonly ?string $middle_name;
    public readonly string $last_name;
    public readonly string $username;
    public readonly string $email;
    public readonly string $password;
    public readonly ?string $avatar;
    public readonly ?string $bio;
    public readonly string $created_at;

    /**
    * @param array<string, mixed> $row
    */
    private function hydrate(array $row): self {
        $user                = new self();
        $user->id            = $row['id'];
        $user->first_name    = $row['first_name'];
        $user->middle_name   = $row['middle_name'] ?? null;
        $user->last_name     = $row['last_name'];
        $user->username      = $row['username'];
        $user->email         = $row['email'];
        $user->password      = $row['password'];
        $user->avatar        = $row['avatar'] ?? null;
        $user->bio           = $row['bio'] ?? null;
        $user->gender        = $row['gender'];
        $user->created_at    = $row['created_at'];

        return $user;
    }

    /** 
    * @param array $data
    * */
    public function createUser(array $data): bool {
        $stmt = $this->pdo->prepare(
            "insert into {$this->table} (
                first_name, 
                middle_name, 
                last_name,
                email, 
                username, 
                password,
                gender) 
             values (:first_name, :middle_name, :last_name, :email, :username, :password, :gender)");
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

    public function findByUsername(string $username): ?self {
        $stmt = $this->pdo->prepare("
            select * from {$this->table} where username = :username
        ");

        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    /**
     * @return self[]
     */
    public function search(string $query): array {

        // just the users ONLY for a now
        $stmt = $this->pdo->prepare("
            SELECT first_name, middle_name, last_name, username, avatar from {$this->table}
            WHERE first_name ILIKE :query
            OR last_name ILIKE :query
            OR middle_name ILIKE :query
            LIMIT 20
        ");

        // % wildcards allow partial matching on both ends
        $stmt->execute(['query' => '%' . $query . '%']);
        
        return array_map(function($row) {
            $dto                = new UserSearchResult();
            $dto->first_name    = $row['first_name'];
            $dto->middle_name   = $row['middle_name'];
            $dto->last_name     = $row['last_name'];
            $dto->username      = $row['username'];
            $dto->avatar        = $row['avatar'];

            return $dto;
        }, $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

}


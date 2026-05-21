<?php

namespace App\Models;

class PasswordReset extends Model {
    protected string $table = 'password_reset';

    public readonly string $token;
    public readonly string $email;
    public readonly string $expires_at;

    /**
     * @param array $row
     */
    private function hydrate(array $row): self {
        $reset               = new self();
        $reset->token        = $row['token'];
        $reset->email        = $row['email'];
        $reset->expires_at   = $row['expires_at'];

        return $reset;
    }

    public function create(string $token, string $email, string $expires_at): bool {
        $stmt = $this->pdo->prepare("
            insert into {$this->table} (token, email, expires_at)
            values (:token, :email, :expires_at)
        ");

        return $stmt->execute([ 
            'token' => $token,
            'email' => $email,
            'expires_at' => $expires_at
        ]);
    }

    public function findByToken(string $token): ?self {
        $stmt = $this->pdo->prepare("
             select * from {$this->table} where token = :token
         ");
 
         $stmt->execute([':token' => $token]);
         $row = $stmt->fetch(\PDO::FETCH_ASSOC);
 
         return $row ? $this->hydrate($row) : null;
     }
 
     public function deleteByToken(string $token): bool {
         $stmt = $this->pdo->prepare("
             delete from {$this->table} where token = :token
         ");
 
         return $stmt->execute([':token' => $token]);
     }
 
     public function deleteByEmail(string $email): bool {
         $stmt = $this->pdo->prepare("
             delete from {$this->table} where email = :email
         ");
 
         return $stmt->execute([':email' => $email]);
     }
}

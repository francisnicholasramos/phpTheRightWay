<?php 

namespace App\Models;

class Friend extends Model {
    protected string $table = 'friends';

    public readonly string $requester_id; // user who initiates friend request
    public readonly string $recipient_id; 
    public readonly string $status; 
    public readonly string $created_at; 

    /**
     * @param array<string, mixed> $row
     */
    private function hydrate(array $row): self {
        $friend                 = new self();
        $friend->requester_id   = $row['requester_id'];
        $friend->recipient_id   = $row['recipient_id'];
        $friend->status         = $row['status'];
        $friend->created_at     = $row['created_at'];

        return $friend;
    }

    /** 
     * @param array $data
     */
    public function createFriendRequest(string $requester_id, string $recipient_id): bool {
        $stmt = $this->pdo->prepare("
            insert into {$this->table} (requester_id, recipient_id)
            values (:requester_id, :recipient_id)
        ");

        return $stmt->execute([
            'requester_id' => $requester_id,
            'recipient_id' => $recipient_id
        ]);
    }

    public function acceptRequest(string $requester_id, string $recipient_id): bool {
        $stmt = $this->pdo->prepare("
            update {$this->table} SET status = 'friends'
            where requester_id = :requester_id AND recipient_id = :recipient_id
        ");

        return $stmt->execute([
            'requester_id' => $requester_id, 
            'recipient_id' => $recipient_id
        ]);
    }

    public function deleteFriendRequest(string $requester_id, string $recipient_id): bool {
        $stmt = $this->pdo->prepare("
            delete from {$this->table} 
            WHERE requester_id = :requester_id AND recipient_id = :recipient_id
        ");

        return $stmt->execute([
            'requester_id' => $requester_id,
            'recipient_id' => $recipient_id
        ]);
    }

    public function hasPendingRequest(string $requester_id, string $recipient_id): bool {
        $stmt = $this->pdo->prepare("
            select 1 from {$this->table}
            WHERE requester_id = :requester_id 
            AND recipient_id = :recipient_id
            AND status = 'pending'
        ");

        $stmt->execute([
            'requester_id' => $requester_id, 
            'recipient_id' => $recipient_id
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function hasIncomingRequest(string $requester_id, string $recipient_id): bool {
        $stmt = $this->pdo->prepare("
            select 1 from {$this->table}
            WHERE requester_id = :requester_id 
            AND recipient_id = :recipient_id
            AND status = 'pending'
        ");

        $stmt->execute([
            'requester_id' => $requester_id, 
            'recipient_id' => $recipient_id
        ]);

        return (bool) $stmt->fetchColumn();
    }

    /**
     * 1st query: YOU sent the request — friend is in recipient_id
     * 2nd query: YOU received the request — friend is in requester_id
     * UNION merges both into one complete friend list
     */
    public function getFriends(string $user_id, int $count=5, int $offset=0): array {
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.username, u.first_name, u.middle_name, u.last_name, u.avatar
            FROM {$this->table} f
            JOIN users u ON u.id = f.recipient_id
            WHERE f.requester_id = :user_id1 AND f.status = 'friends'

            UNION

            SELECT u.id, u.username, u.first_name, u.middle_name, u.last_name, u.avatar
            FROM {$this->table} f
            JOIN users u ON u.id = f.requester_id
            WHERE f.recipient_id = :user_id2 AND f.status = 'friends'

            LIMIT :count OFFSET :offset
        ");

        $stmt->bindValue(':user_id1', $user_id);
        $stmt->bindValue(':user_id2', $user_id);
        $stmt->bindValue(':count', $count, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function isFriends(string $user_id, string $other_id): bool {
      $stmt = $this->pdo->prepare("
          select 1 from {$this->table} 
          WHERE status = 'friends'
          AND ( 
              (requester_id = :user_id AND recipient_id = :other_id)
                OR
              (requester_id = :other_id AND recipient_id = :user_id)
          )
      ");

      $stmt->execute([ 
          'user_id' => $user_id,
          'other_id' => $other_id
      ]);

      return (bool) $stmt->fetchColumn();
    }
}

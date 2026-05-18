<?php

namespace App\Models;

use App\Dto\AboutMeDto;
use App\Dto\EducationDto;
use App\Dto\WorkDto;

class UserProfile extends Model {
    protected string $table = 'user_profile';
    public array $interests;
    public array $hobbies;
    public array $favorite_music;
    public ?array $education;
    public ?array $work;

    private function parseArray(?string $val): array {
        if (!$val || $val === '{}') return [];
        $val = trim($val, '{}'); // removes curlies
        return array_map(fn($v) => trim($v, '" '), explode(',', $val));
    }

    public function findByUserId(string $userId): ?self {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) return null;

        $profile               = new self();
        $profile->interests    = $this->parseArray($row['interests']);
        $profile->hobbies      = $this->parseArray($row['hobbies']);
        $profile->favorite_music = $this->parseArray($row['favorite_music']);
        $profile->education      = $row['education'] ? json_decode($row['education'], true) : null;
        $profile->work           = $row['work'] ? json_decode($row['work'], true) : null;

        return $profile;
    }

    public function upsert(string $userId, AboutMeDto $data): bool {
        $stmt = $this->pdo->prepare("
            insert into {$this->table} (user_id, interests, hobbies, favorite_music)
            VALUES (:user_id, :interests::TEXT[], :hobbies::TEXT[], :favorite_music::TEXT[])
            ON CONFLICT (user_id) DO UPDATE SET
                interests      = EXCLUDED.interests,
                hobbies        = EXCLUDED.hobbies,
                favorite_music = EXCLUDED.favorite_music
        ");
        return $stmt->execute([
            'user_id'        => $userId,
            'interests'      => '{' . implode(',', $data->interests) . '}',
            'hobbies'        => '{' . implode(',', $data->hobbies) . '}',
            'favorite_music' => '{' . implode(',', $data->favorite_music) . '}',
        ]);
    }

    public function upsertEducation(string $userId, EducationDto $data): bool {
        $stmt = $this->pdo->prepare("
            insert into {$this->table} (user_id, education)
            values (:user_id, :education::JSONB)
            ON CONFLICT (user_id) DO UPDATE SET
                education = EXCLUDED.education
        ");

        return $stmt->execute([
            'user_id'   => $userId,
            'education' => json_encode($data->education),
        ]);
    }

    public function upsertWork(string $userId, WorkDto $data): bool {
        $stmt = $this->pdo->prepare("
            insert into {$this->table} (user_id, work)
            values (:user_id, :work::JSONB)
            ON CONFLICT (user_id) DO UPDATE SET
                work = EXCLUDED.work
        ");

        return $stmt->execute([
            'user_id' => $userId,
            'work'    => json_encode($data->work),
        ]);
    }
}

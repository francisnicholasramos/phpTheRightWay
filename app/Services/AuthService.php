<?php

namespace App\Services;

use App\Models\User;
use Core\Session;

class AuthService {
    private static ?User $user=null;

    public static function attempt(string $email, string $password): bool {
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        $session = new Session();
        $session->set('user_id', $user['id']);
        self::$user = $user;

        return true;
    }
}

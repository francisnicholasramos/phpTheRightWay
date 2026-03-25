<?php

namespace App\Services;

use App\Models\User;
use Core\Session;

class AuthService {
    private static ?User $user=null;

    public static function check(): bool {
        $session = new Session();
        return $session->has('user_id');
    }

    public static function user(): ?User {
        if (self::$user !== null) {
            return self::$user; // Class::method()
        }

        $session = new Session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return null;
        }

        $userModel = new User();
        self::$user = $userModel->findById($userId);

        return self::$user;
    }

    public static function signup(
        string $email, 
        string $username, 
        string $password
    ): bool {
        $userModel = new User();

        if ($userModel->findByEmail($email)) {
            return false;
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        return $userModel->createUser([
            'email' => $email,
            'username' => $username,
            'password' => $passwordHash
        ]);
    }

    public static function attempt(string $email, string $password): bool {
        $userModel = new User();

        if (empty($email) || empty($password)) {
            return null;
        }

        $user = $userModel->findByEmail($email);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user->password)) {
            return false;
        }

        $session = new Session();
        $session->set('user_id', $user->id);
        self::$user = $user;

        return true;
    }

    public static function logout(): void {
        $session = new Session();
        $session->destroy();
        self::$user = null;
    }
}

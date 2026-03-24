<?php 

namespace Core;

class Session {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function set(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, mixed $default=null): mixed {
        return $_SESSION[$key] ?? $default;
    }

    public function has(string $key): bool {
        return isset($_SESSION[$key]);
    }

    public function remove(string $key): void {
        unset($_SESSION[$key]);
    }

    public function destroy(): void {
        session_destroy();
    }

    public function flash(string $key, mixed $value=null): mixed {
        if ($value !== null) {
            $_SESSION['flash'][$key] = $value;
            return null;
        }

        $flash = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $flash;
    }
}

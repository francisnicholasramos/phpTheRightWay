<?php

namespace Core;

class Request {
    public function __construct() {
        // literally nothing. just accessing the superglobals directly
    }

    public function get(string $key, mixed $default=null): mixed {
        return $_GET[key] ?? $default;
    }

    public function post(string $key, mixed $default=null): mixed {
        return $_POST[key] ?? $default;
    }

    public function method(): string {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isPost(): bool {
        return $this->method() === 'POST';
    }

    public function isGet(): bool {
        return $this->method() === 'GET';
    }
}

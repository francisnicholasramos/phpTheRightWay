<?php

namespace Core;

class Route {
    private string $method;
    private string $path;
    private array $handler;
    private ?string $middleware = null;
    
    public function __construct(string $method, string $path, array $handler) {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
    }

    public function middleware(string $middleware): self {
        $this->middleware = $middleware;
        return $this;
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function getPath(): string {
        return $this->path;
    }

    public function getHandler(): array {
        return $this->handler;
    }

    public function getMiddleware(): ?string {
        return $this->middleware;
    }
}

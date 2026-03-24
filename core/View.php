<?php

namespace Core;

class View {
    public static function render(string $view): void {
        $viewPath = __DIR__ . "/../resources/views/{$view}.php";

        if (!file_exists($viewPath)) {
            throw new \Exception("View not found: {$view}");
        }

        require $viewPath;
    }
}

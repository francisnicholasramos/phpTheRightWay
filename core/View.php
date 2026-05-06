<?php

namespace Core;

class View {
    /** 
     * @param array $data
     **/
    public static function render(string $view, array $data=[]): void {
        extract($data);

        $viewPath = __DIR__ . "/../resources/views/{$view}.php";

        if (!file_exists($viewPath)) {
            throw new \Exception("Unable to locate resource file: {$view}");
        }

        require $viewPath;
    }
}

<?php 

namespace Core;

class Response {
    public function redirect(string $path): void {
        header("Location: {$path}");
        exit;
    }
    /**
    * @param array $data
    */
    public function json(array $data): void {
        header('Content-Type: applicaton/json');
        echo json_encode($data);
        exit;
    }
}

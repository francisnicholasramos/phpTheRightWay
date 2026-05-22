<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\UserPhoto;
use App\Services\AuthService;
use Core\Response;
use Core\View;

class PhotoController {
    public function photoList(string $username): void {
        if (!AuthService::check()) {
            (new Response())->redirect('/login');
            return;
        }

        $user = (new User())->findByUsername($username);

        if (!$user) {
            http_response_code(404);
            echo "User not found.";
            return;
        }

        $photos = (new UserPhoto())->getByUserId($user->id);

        View::render('components/photos', [
            'user' => $user,
            'photos' => $photos,
        ]);
    }
}

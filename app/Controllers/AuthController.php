<?php

namespace App\Controllers;

use App\Services\AuthService;
use Core\Request;
/* use Core\Response; */

class AuthController {
    /* public function show() { */
    /**/
    /* } */

    public function login(): void {
        $request = new Request();
        $email = $request->post('email');
        $password = $request->post('password');

        if (AuthService::attempt($email, $password)) {
            /* (new Response())-> */
        }
    }
}


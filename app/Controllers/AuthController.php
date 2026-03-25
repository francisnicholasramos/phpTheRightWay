<?php

namespace App\Controllers;

use App\Services\AuthService;
use Core\Request;
use Core\Response;
use Core\View;

class AuthController {
    public function loginPage(): void {
        View::render('auth/Login');
    }

    public function signUpPage(): void {
        View::render('auth/Signup');
    }

    public function signUpHandler(): void {
        $request = new Request();
        $email = $request->post('email');
        $username = $request->post('username');
        $password = $request->post('password');

        if (AuthService::signup($email, $username, $password)) {
            (new Response())->redirect('/login');
        }

        (new Response())->redirect('/signup');
    }

    public function loginHandler(): void {
        $request = new Request();
        $email = $request->post('email');
        $password = $request->post('password');

        if (AuthService::attempt($email, $password)) {
            (new Response())->redirect('/feed');
        }

        $session = new \Core\Session();
        $session->flash('error', 'Invalid credentials');

        (new Response())->redirect('/login');
    }

    public function logout(): void {
        AuthService::logout();
        (new Response())->redirect('/login');
    }
}


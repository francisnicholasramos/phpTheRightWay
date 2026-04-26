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
        
        $first_name = $request->post('firstname');
        $middle_name = $request->post('middlename');
        $last_name = $request->post('lastname');
        $email = $request->post('email');
        $password = $request->post('password');
        $gender = $request->post('gender');
        
        if (empty($first_name)) {
            $session = new \Core\Session();
            $session->flash('error' , 'Firstname field cannot be empty.');

            (new Response())->redirect('/signup');
            return;
        }

        if (empty($last_name)) {
            $session = new \Core\Session();
            $session->flash('error' , 'Lastname field cannot be empty.');

            (new Response())->redirect('/signup');
            return;
        }

        if (empty($email)) {
            $session = new \Core\Session();
            $session->flash('error' , 'Email field cannot be empty.');

            (new Response())->redirect('/signup');
            return;
        }

        if (empty($password)) {
            $session = new \Core\Session();
            $session->flash('error' , 'Password field cannot be empty.');

            (new Response())->redirect('/signup');
            return;
        }

        if (empty($gender)) {
            $session = new \Core\Session();
            $session->flash('error' , 'Gender is required.');

            (new Response())->redirect('/signup');
            return;
        }

        if (AuthService::signup($first_name, $middle_name, $last_name, $email, $password, $gender)) {
            (new Response())->redirect('/login');
        }

        (new Response())->redirect('/signup');
    }

    public function loginHandler(): void {
        $request = new Request();
        $email = $request->post('email');
        $password = $request->post('password');

        if (empty($email)) {
            $session = new \Core\Session();
            $session->flash('error' , 'Email field cannot be empty.');

            (new Response())->redirect('/login');
            return;
        }

        if (empty($password)) {
            $session = new \Core\Session();
            $session->flash('error' , 'Password field cannot be empty.');

            (new Response())->redirect('/login');
            return;
        }

        if (AuthService::attempt($email, $password)) {
            (new Response())->redirect('/feed');
        }

        (new Response())->json(['error' => 'Invalid credentials'], 401);

        (new Response())->redirect('/login');
    }

    public function logout(): void {
        AuthService::logout();
        (new Response())->redirect('/login');
    }
}


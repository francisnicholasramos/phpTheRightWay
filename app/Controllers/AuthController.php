<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\PasswordResetService;
use Core\Request;
use Core\Response;
use Core\Session;
use Core\View;

class AuthController {
    public function index(): void {
        View::render('auth/Index');
    }

    public function loginPage(): void {
        View::render('auth/Login');
    }

    public function signUpPage(): void {
        View::render('auth/Signup');
    }

    public function forgotPasswordPage(): void {
        View::render('auth/Login');
    }

    public function signUpHandler(): void {
        $request = new Request();
        
        $first_name = $request->post('firstname');
        $middle_name = $request->post('middlename');
        $last_name = $request->post('lastname');
        $email = $request->post('email');
        $password = $request->post('password');
        $birthday = $request->post('birthday');
        $gender = $request->post('gender');
        
        if (empty($first_name)) {
            $session = new \Core\Session();
            $session->flash('error' , 'Firstname is required.');

            (new Response())->redirect('/register');
            return;
        }

        if (empty($last_name)) {
            $session = new \Core\Session();
            $session->flash('error' , 'Lastname is required.');

            (new Response())->redirect('/register');
            return;
        }

        if (empty($email)) {
            $session = new \Core\Session();
            $session->flash('error' , 'Email is required.');

            (new Response())->redirect('/register');
            return;
        }

        if (empty($password)) {
            $session = new \Core\Session();
            $session->flash('error' , 'Password is required.');

            (new Response())->redirect('/register');
            return;
        }

        if (empty($birthday)) {
            $session = new \Core\Session();
            $session->flash('error' , 'Birthday is required.');

            (new Response())->redirect('/register');
            return;
        }

        if (empty($gender)) {
            $session = new \Core\Session();
            $session->flash('error' , 'Gender is required.');

            (new Response())->redirect('/register');
            return;
        }

        if (AuthService::signup($first_name, $middle_name, $last_name, $email, $password, $birthday, $gender)) {
            (new Response())->redirect('/login');
        }

        (new Response())->redirect('/register');
    }

    public function loginHandler(): void {
        $request = new Request();
        $email = $request->post('email');
        $password = $request->post('password');
        $back = $_SERVER['HTTP_REFERER'] ?? '/login';

        if (empty($email)) {
            $session = new \Core\Session();
            $session->flash('login_error' , 'Email field cannot be empty.');

            (new Response())->redirect($back);
            return;
        }

        if (empty($password)) {
            $session = new \Core\Session();
            $session->flash('login_error' , 'Password field cannot be empty.');

            (new Response())->redirect($back);
            return;
        }

        if (AuthService::attempt($email, $password)) {
            (new Response())->redirect('/feed');
        }

        $session = new \Core\Session();
        $session->flash('login_error', 'Invalid email or password.');

        (new Response())->redirect($back);
    }

    public function forgotPasswordHandler(): void {
        $request = new Request();
        $email = $request->post('email');
        $session = new Session();
         
        if (empty($email)) {
            $session->flash('error', 'Email is required.');
            (new Response())->redirect('/forgot-password');
            return;
        }

        PasswordResetService::sendResetLink($email);

        $session->flash('success', 'Reset link has been sent to your email.');
        (new Response())->redirect('/forgot-password');
    }

    public function resetPasswordPage(): void {
        $request = new Request();
        $token = $request->get('token');
        $session = new Session();

        if (empty($token) || !PasswordResetService::validateToken($token)) {
            $session->flash('error', 'Invalid or expired link.');

            (new Response())->redirect('/forgot-password');
            return;
        }

        View::render('auth/ResetPassword', [ 
            'token' => $token
        ]);
    }

    public function resetPasswordHandler(): void {
        $request  = new Request();
        $token    = $request->post('token');
        $password = $request->post('password');
        $confirm  = $request->post('password_confirm');
        $session  = new Session();

        if (empty($token) || empty($password) || empty($confirm)) {
            $session->flash('error', 'All fields are required.');

            (new Response())->redirect('/reset-password?token=' . urlencode($token ?? ''));
            return;
        }

        if ($password !== $confirm) {
            $session->flash('error', 'Passwords do not match.');

            (new Response())->redirect('/reset-password?token=' . urlencode($token));
            return;
        }

        if (!PasswordResetService::resetPassword($token, $password)) {
            $session->flash('error', 'This reset link is invalid or expired.');

            (new Response())->redirect('/forgot-password');
            return;
        }

        $session->flash('success', 'Password reset successfully. You can now log in.');
        (new Response())->redirect('/login');
    }

    public function logout(): void {
        AuthService::logout();
        (new Response())->redirect('/login');
    }
}


<?php

use App\Controllers\AuthController;
use App\Controllers\FeedController;
use Core\Router;

Router::get('/login', [AuthController::class, 'loginPage']);
Router::get('/signup', [AuthController::class, 'signUpPage']);
Router::get('/feed', [FeedController::class, 'index'])->middleware('Authenticate');

Router::post('/login', [AuthController::class, 'loginHandler']);
Router::post('/signup', [AuthController::class, 'signUpHandler']);

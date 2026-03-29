<?php

use App\Controllers\AuthController;
use App\Controllers\FeedController;
use App\Controllers\PostController;
use Core\Router;

Router::get('/login', [AuthController::class, 'loginPage']);
Router::get('/signup', [AuthController::class, 'signUpPage']);
Router::get('/feed', [FeedController::class, 'index'])->middleware('Authenticate');

Router::post('/login', [AuthController::class, 'loginHandler']);
Router::post('/logout', [AuthController::class, 'logout']);
Router::post('/signup', [AuthController::class, 'signUpHandler']);
Router::post('/createPost', [PostController::class, 'createPostHandler']);

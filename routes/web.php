<?php

use App\Controllers\AuthController;
use App\Controllers\FeedController;
use Core\Router;

Router::get('/login', [AuthController::class, 'show']);
Router::get('/feed', [FeedController::class, 'index'])->middleware('Authenticate');

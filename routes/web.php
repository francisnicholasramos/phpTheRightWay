<?php

use App\Controllers\AuthController;
use App\Controllers\FeedController;
use App\Controllers\PostController;
use App\Controllers\LikeController;
use App\Controllers\CommentController;
use App\Controllers\NotificationController;
use App\Controllers\MessageController;
use App\Controllers\ProfileController;
use App\Controllers\SearchController;
use Core\Router;

Router::get('/login', [AuthController::class, 'loginPage']);
Router::get('/register', [AuthController::class, 'signUpPage']);
Router::get('/feed', [FeedController::class, 'index'])->middleware('Authenticate');
Router::get('/notifications/count', [NotificationController::class, 'countHandler'])->middleware('Authenticate');
Router::get('/search', [SearchController::class, 'searchHandler'])->middleware('Authenticate');
/* Router::get('/search/suggest', [SearchController::class, 'suggestHandler'])->middleware('Authenticate'); */
Router::get('/u/{username}', [ProfileController::class, 'viewProfile']);
Router::get('/messages/{chatId}', [MessageController::class, 'showChat'])->middleware('Authenticate');

Router::get('/chat/start/{userId}', [MessageController::class, 'startChatHandler'])->middleware('Authenticate');
Router::get('/messages', [MessageController::class, 'index'])->middleware('Authenticate');

Router::post('/login', [AuthController::class, 'loginHandler']);
Router::post('/logout', [AuthController::class, 'logout']);
Router::post('/signup', [AuthController::class, 'signUpHandler']);
Router::post('/createPost', [PostController::class, 'createPostHandler']);
Router::post('/like', [LikeController::class, 'likeHandler'])->middleware('Authenticate');
Router::post('/postComment', [CommentController::class, 'postCommentHandler'])->middleware('Authenticate');
Router::post('/sendMessage', [MessageController::class, 'sendMessageHandler'])->middleware('Authenticate');

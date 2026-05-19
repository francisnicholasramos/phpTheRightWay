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
use App\Controllers\FriendController;
use App\Controllers\PokeController;
use Core\Router;

Router::get('/login', [AuthController::class, 'loginPage']);
Router::get('/register', [AuthController::class, 'signUpPage']);
Router::get('/feed', [FeedController::class, 'index'])->middleware('Authenticate');
Router::get('/notifications/count', [NotificationController::class, 'countHandler'])->middleware('Authenticate');
Router::get('/search', [SearchController::class, 'searchHandler'])->middleware('Authenticate');
Router::get('/u/{username}', [ProfileController::class, 'viewProfile']);
Router::get('/u/{username}/friends', [FriendController::class, 'friendList'])->middleware('Authenticate');
Router::get('/u/{username}/friends/more', [FriendController::class, 'loadMoreFriends'])->middleware('Authenticate');
Router::get('/messages/count', [MessageController::class, 'getUnreadChatHandler'])->middleware('Authenticate');
Router::get('/messages/{chatId}', [MessageController::class, 'showChat'])->middleware('Authenticate');
Router::get('/notifications', [NotificationController::class, 'index'])->middleware('Authenticate');
Router::get('/post/{postId}', [PostController::class, 'viewPost'])->middleware('Authenticate');
Router::get('/profiles/{userId}', [ProfileController::class, 'viewEditProfile'])->middleware('Authenticate');
Router::get('/profiles/{userId}/name', [ProfileController::class, 'viewEditProfile'])->middleware('Authenticate');
Router::get('/profiles/{userId}/personal_details', [ProfileController::class, 'viewEditProfile'])->middleware('Authenticate');
Router::get('/profiles/{userId}/about_me', [ProfileController::class, 'viewEditProfile'])->middleware('Authenticate');
Router::get('/profiles/{userId}/education', [ProfileController::class, 'viewEditProfile'])->middleware('Authenticate');
Router::get('/profiles/{userId}/work', [ProfileController::class, 'viewEditProfile'])->middleware('Authenticate');

Router::get('/chat/start/{userId}', [MessageController::class, 'startChatHandler'])->middleware('Authenticate');
Router::get('/messages', [MessageController::class, 'index'])->middleware('Authenticate');

Router::post('/login', [AuthController::class, 'loginHandler']);
Router::post('/logout', [AuthController::class, 'logout']);
Router::post('/signup', [AuthController::class, 'signUpHandler']);
Router::post('/createPost', [PostController::class, 'createPostHandler'])->middleware('Authenticate');
Router::post('/editPost', [PostController::class, 'editPostHandler'])->middleware('Authenticate');
Router::post('/like', [LikeController::class, 'likePostHandler'])->middleware('Authenticate');
Router::post('/postComment', [CommentController::class, 'postCommentHandler'])->middleware('Authenticate');
Router::post('/sendMessage', [MessageController::class, 'sendMessageHandler'])->middleware('Authenticate');
Router::post('/friend-request', [FriendController::class, 'sendFriendRequest'])->middleware('Authenticate');
Router::post('/cancel-request', [FriendController::class, 'cancelFriendRequest'])->middleware('Authenticate');
Router::post('/accept-request', [FriendController::class, 'acceptRequest'])->middleware('Authenticate');
Router::post('/decline-request', [FriendController::class, 'declineRequest'])->middleware('Authenticate');
Router::post('/poke', [PokeController::class, 'pokeHandler'])->middleware('Authenticate');
Router::post('/updateProfile', [ProfileController::class, 'updateProfile'])->middleware('Authenticate');
Router::post('/profiles/{userId}/name', [ProfileController::class, 'changeName'])->middleware('Authenticate');
Router::post('/profiles/{userId}/personal_details', [ProfileController::class, 'editPersonalDetails'])->middleware('Authenticate');
Router::post('/profiles/{userId}/about_me', [ProfileController::class, 'editAboutMe'])->middleware('Authenticate');
Router::post('/profiles/{userId}/education', [ProfileController::class, 'editEducation'])->middleware('Authenticate');
Router::post('/profiles/{userId}/work', [ProfileController::class, 'editWork'])->middleware('Authenticate');

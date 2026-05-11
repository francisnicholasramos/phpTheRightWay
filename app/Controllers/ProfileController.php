<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Friend;
use App\Models\UserProfile;
use App\Models\ChatParticipant;
use App\Services\AuthService;
use App\Services\ProfileService;
use App\Dto\ChangeNameDto;
use Core\Response;
use Core\Session;
use Core\View;

class ProfileController {
    public function viewProfile(string $username): void {
        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if (!$user) {
            http_response_code(404);
            echo "User not found";
            return;
        }

        $postModel = new Post();
        $posts = $postModel->getByUserId($user->id);

        $existingChatId = null; // check if this user has already convo with current logged-user 
        $isPending = false; // for the user who triggers the event 
        $isIncoming = false; // for user WHO will receive the event (friend request)
        $isFriends = false; // if it's already friends with the other user

        if (AuthService::check()) {
            $currentUser = AuthService::user()->id;
            $chat = (new ChatParticipant())->getDirectChatWith($currentUser, $user->id);
            $existingChatId = $chat ? $chat['id'] : null;
            $isPending = (new Friend())->hasPendingRequest($currentUser, $user->id);
            $isIncoming = (new Friend())->hasIncomingRequest($user->id, $currentUser);
            $isFriends = (new Friend())->isFriends($currentUser, $user->id);
        }

        View::render('components/profile', [
            'user' => $user,
            'existingChatId' => $existingChatId,
            'isPending' => $isPending,
            'isIncoming' => $isIncoming,
            'isFriends' => $isFriends
        ]);
    }

    public function viewEditProfile(string $user_id): void{
        $user = AuthService::user();
        View::render('components/edit-profile', [
            'user' => $user
        ]);
    }

    public function changeName(): void {
        $user = AuthService::user();

        if (empty($_POST['first_name'])) {
            (new Session())->flash('error', 'First name cannot be empty.');
            (new Response())->redirect('/profiles/' . $user->id . '/name');
            return;
        }

        if (empty($_POST['last_name'])) {
            (new Session())->flash('error', 'Last name cannot be empty.');
            (new Response())->redirect('/profiles/' . $user->id . '/name');
            return;
        }

        if (empty($_POST['password_authority'])) {
            (new Session())->flash('error', 'Password is required.');
            (new Response())->redirect('/profiles/' . $user->id . '/name');
            return;
        }

        if (!password_verify($_POST['password_authority'], $user->password)) {
            (new Session())->flash('error', 'Incorrect password.');
            (new Response())->redirect('/profiles/' . $user->id . '/name');
            return;
        }

        $dto               = new ChangeNameDto();
        $dto->id           = $user->id;
        $dto->first_name   = $_POST['first_name'];
        $dto->middle_name  = $_POST['middle_name'] ?? '';
        $dto->last_name    = $_POST['last_name'];

        $changed = (new ProfileService())->changeName($dto);

        if (!$changed) {
            (new Session())->flash('error', 'You can\'t change your name again for 30 days.');
            (new Response())->redirect('/profiles/' . $user->id . '/name');
            return;
        }

        (new Response())->redirect('/u/' . $user->username);
    }
}

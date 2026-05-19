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
use App\Dto\EditPersonalDetailsDto;
use App\Dto\AboutMeDto;
use App\Dto\EducationDto;
use App\Dto\WorkDto;
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

        $profile = (new UserProfile())->findByUserId($user->id);

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

        $friends = (new Friend())->getFriends($user->id);

        View::render('components/profile', [
            'user' => $user,
            'profile' => $profile,
            'friends' => $friends,
            'existingChatId' => $existingChatId,
            'isPending' => $isPending,
            'isIncoming' => $isIncoming,
            'isFriends' => $isFriends
        ]);
    }

    public function viewEditProfile(string $user_id): void{
        if (AuthService::user()->id !== $user_id) {
          http_response_code(403);
          echo "Forbidden";
          return;
        }

        $user = AuthService::user();
        $profile = (new UserProfile())->findByUserId($user_id);
        View::render('components/edit-profile', [
            'user' => $user,
            'profile' => $profile
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

    public function editPersonalDetails(): void {
        $user = AuthService::user();
      
        if (empty($_POST['username'])) {
            (new Session())->flash('error', 'Username cannot be empty.');
            (new Response())->redirect('/profiles/' . $user->id . '/personal_details');
            return;
        }

        // no spaces to avoid urlencoding issues
        if (str_contains($_POST['username'], ' ')) {
            (new Session())->flash('error', 'Username cannot contain spaces.');
            (new Response())->redirect('/profiles/' . $user->id . '/personal_details');
            return;
        }
        
        if (empty($_POST['birthday'])) {
            (new Session())->flash('error', 'Birthday cannot be empty.');
            (new Response())->redirect('/profiles/' . $user->id . '/personal_details');
            return;
        }

        if (empty($_POST['gender'])) {
            (new Session())->flash('error', 'Gender cannot be empty.');
            (new Response())->redirect('/profiles/' . $user->id . '/personal_details');
            return;
        }

        $data              = new EditPersonalDetailsDto();
        $data->id          = $user->id;
        $data->bio         = trim($_POST['bio'] ?? '') ?: null;
        $data->username    = trim($_POST['username']);
        $data->hometown    = trim($_POST['hometown'] ?? '') ?: null;
        $data->birthday    = $_POST['birthday'];
        $data->gender      = $_POST['gender'];

        $updated = (new ProfileService())->editPersonalDetails($data);

        if ($updated) {
            (new Session())->flash('error', $updated);
            (new Response())->redirect('/profiles/' . $user->id . '/personal_details');
            return;
        }

        (new Response())->redirect('/profiles/' . $user->id . '/personal_details');
    }

    public function editAboutMe(): void {
        $user = AuthService::user();

        $data                 = new AboutMeDto();
        $data->id             = $user->id;
        $data->interests      = array_map('trim', explode(',', $_POST['interests'] ?? ''));
        $data->hobbies        = array_map('trim', explode(',', $_POST['hobbies'] ?? ''));
        $data->favorite_music = array_map('trim', explode(',', $_POST['favorite_music'] ?? ''));

        $arr_data = (new ProfileService())->editAboutMe($data);

        if (!$arr_data) {
          (new Session())->flash('error', 'Something went wrong.');
          (new Response())->redirect('/profiles/' . $user->id . '/about_me');
          return;
        }

        (new Response())->redirect('/profiles/' . $user->id . '/about_me');
    }

    public function editEducation(): void {
        $user = AuthService::user();

        $data             = new EducationDto();
        $data->id         = $user->id;
        $data->education  = [
            'school'     => trim($_POST['education']['school'] ?? '') ?: null,
            'field'      => trim($_POST['education']['field'] ?? '') ?: null,
        ];

        $arr_data = (new ProfileService())->editEducation($data);

        if (!$arr_data) {
            (new Session())->flash('error', 'Something went wrong.');
            (new Response())->redirect('/profiles/' . $user->id . '/education');
            return;
        }

        (new Response())->redirect('/profiles/' . $user->id . '/education');
    }

    public function editWork(): void {
        $user = AuthService::user();

        $data       = new WorkDto();
        $data->id   = $user->id;
        $data->work = [
            'company'  => trim($_POST['work']['company'] ?? '') ?: null,
            'position' => trim($_POST['work']['position'] ?? '') ?: null,
        ];

        $result = (new ProfileService())->editWork($data);

        if (!$result) {
            (new Session())->flash('error', 'Something went wrong.');
            (new Response())->redirect('/profiles/' . $user->id . '/work');
            return;
        }

        (new Response())->redirect('/profiles/' . $user->id . '/work');
    }
}

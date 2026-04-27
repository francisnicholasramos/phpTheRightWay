<?php

namespace App\Dto;

class UserSearchResult {
    public string $first_name;
    public ?string $middle_name;
    public string $last_name;
    public string $username;
    public ?string $avatar;
}

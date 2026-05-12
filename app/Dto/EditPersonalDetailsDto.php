<?php

namespace App\Dto;

class EditPersonalDetailsDto {
    public string $id;
    public ?string $bio;
    public string $username;
    public ?string $hometown;
    public string $birthday;
    public string $gender;
}

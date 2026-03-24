<?php

use App\Controllers\AuthController;

return [
    'GET /login' => [AuthController::class, 'show'],
];

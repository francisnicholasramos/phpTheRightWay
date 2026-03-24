<?php

namespace App\Controllers;

use Core\View;

class FeedController {
    public function index(): void {
        View::render('feed/index');
    }
}

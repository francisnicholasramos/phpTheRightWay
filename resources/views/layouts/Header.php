<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>thesocialnetwork</title>
        <link href="/css/main.css" rel="stylesheet">
        <link href="/css/header.css" rel="stylesheet">
    </head>
    <body>
    <header>
        <div class="niko">
            <img src="/assets/4000.png" class="binary-niko" alt="logo"/>
        </div>
        <div class="social-network">
            <img src="/assets/social_network.png" class="logo" alt="logo"/>
            <nav class="nav-links">
                <?php if (!isset($_SESSION['user_id'])) : ?>
                    <li><a href="/login">login</a></li>
                    <li><a href="/signup">register</a></li>
                <?php else: ?>
                    <li><a href="/feed">home</a></li>
                    <li><a href="">profile</a></li>
                    <li><a href="">messages</a></li>
                    <li><a href="">notifications</a></li>
                    <li><a href="">friends</a></li>
                <?php endif; ?>
            </nav>
        </div>
    </header>
